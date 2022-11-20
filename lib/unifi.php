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
		$device = $this->client->stat_client($mac)[0];
		$name = $this->get_name($device);
		$is_blocked = $device->blocked ? '' : ' NOT';

		echo $name . ':	' . $is_blocked . ' blocked' . PHP_EOL;
	}


	public function block($mac) {
		$this->client->block_sta($mac);
		$this->block_status($mac);
	}


	public function unblock($mac) {
		$res = $this->client->unblock_sta($mac);
		$this->block_status($mac);
	}


	public function list_clients () {
		$res = $this->client->list_clients();
		foreach ($res as $device) {
			echo $device->ip. '	' . $device->mac . '	' . $this->get_name($device) . PHP_EOL;
		}
	}



	private function get_name($device) {
		if (isset($device->name)) return $device->name;
		if (isset($device->hostname)) return $device->hostname;
		return '<NO NAME>';
	}
}
