<?php

class Unifi {
	protected $client;

	public function __construct () {
		$username = Env::get('USERNAME');
		$password = Env::get('PASSWORD');
		$hostname = Env::get('HOSTNAME', 'https://192.168.1.2');
		$site_id = Env::get('SITEID', 'default');
		$version = Env::get('VERSION', '7.2.95');

		$this->client = new Client($username, $password, $hostname, $site_id, $version);
		$this->client->login();
	}


	public function block_status($mac) {
		$mac = $this->get_mac($mac);
		$device = $this->client->stat_client($mac)[0];
		$name = $this->get_name($device);
		$is_blocked = $device->blocked ? '' : ' NOT';

		echo $name . ':	' . $is_blocked . ' blocked' . PHP_EOL;
	}


	public function block($mac) {
		$mac = $this->get_mac($mac);
		$this->client->block_sta($mac);
		$this->block_status($mac);
	}


	public function unblock($mac) {
		$mac = $this->get_mac($mac);
		$res = $this->client->unblock_sta($mac);
		$this->block_status($mac);
	}


	public function list_clients () {
		$res = $this->get_clients();
		foreach ($res as $device) {
			$ip = substr($device['ip'] . '     ', 0, 15);
			echo $device['mac'] . '  ' . $ip . ' ' . $device['name'] . PHP_EOL;
		}
	}


	private function get_mac ($name_or_mac) {
		$is_mac = filter_var($name_or_mac, FILTER_VALIDATE_MAC);
		if ($is_mac !== false) return $name_or_mac;

		// find by name
		$clients = $this->get_clients();
		$key = array_search($name_or_mac, array_column($clients, 'name'));

		$clients = $this->get_sessions();
		$key = array_search($name_or_mac, array_column($clients, 'name'));

		if (!empty($key)) return $clients[$key]['mac'];

		// Device not found in clients nor in sessions
		echo 'Device not found (may be offline)';
		exit(1);
	}

	private function get_sessions () {
		$res = $this->client->stat_sessions();
		$clients = [];
		if (empty($res)) return [];
		foreach ($res as $device) {
			$clients[] = [
				'name' => $this->get_name($device),
				'ip' => $device->ip ?? '',
				'mac' => $device->mac ?? ''
			];
		}
		return $clients;
	}


	private function get_clients () {
		$res = $this->client->list_clients();
		if (empty($res)) return [];
		$clients = [];
		foreach ($res as $device) {
			$clients[] = [
				'name' => $this->get_name($device),
				'ip' => $device->ip ?? '',
				'mac' => $device->mac ?? ''
			];
		}
		return $clients;
	}


	private function get_name($device) {
		if (isset($device->name)) return $device->name;
		if (isset($device->hostname)) return $device->hostname;
		return '<NO NAME>';
	}
}
