<?php
defined('C5_EXECUTE') or die("Access Denied.");
class DashboardFileManagerController extends Controller {
	public function view() {
		$this->redirect('/dashboard/file_manager/search');
	}
}
