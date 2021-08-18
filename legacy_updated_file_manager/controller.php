<?php defined('C5_EXECUTE') or die("Access Denied.");

class LegacyUpdatedFileManagerPackage extends Package {
	protected $pkgHandle            =   'legacy_updated_file_manager';
	protected $appVersionRequired   =   '5.6.4.0';
	protected $pkgVersion           =   '0.3.0';

	/**
	 * An array of all single pages inside the package
	 * The key represents the page path, and will be used to install/update the page
	 * The value is another associative array of page attributes:
	 * key = The attribute handle
	 * value = The value of the attribute
	 */
	protected $singlePages = array(
		'/dashboard/file_manager/search' => array(
			'meta_keywords' => 'add file, delete file, copy, move, alias, resize, crop, rename, images, title, attribute',
			'icon_dashboard' => 'icon-picture'
		),
		'/dashboard/file_manager/attributes' => array(
			'meta_keywords' => 'file, file attributes, title, attribute, description, rename',
			'icon_dashboard' => 'icon-cog'
		),
		'/dashboard/file_manager/sets' => array(
			'meta_keywords' => 'files, category, categories',
			'icon_dashboard' => 'icon-list-alt'
		),
		'/dashboard/file_manager/add_set' => array(
			'meta_keywords' => 'new file set',
			'exclude_nav' => 1
		)
	);
	
	public function getPackageDescription() {
		return t('Provides a modern file manager using Dropzone.js for multi-file uploading.');
	}

	public function getPackageName() {
		return t('Legacy Updated File Manager');
	}

	public function install() {
		$pkg = parent::install();
		$this->installSinglePages($pkg);

		//Replace the old file manager with the new one
		$newFM = Page::getByPath('/dashboard/file_manager');
		$newFM->updateDisplayOrder(2, $newFM->getCollectionID());
		$newFMSearch = Page::getByPath('/dashboard/file_manager/search');
		$newFMSearch->update(array('cName'=>t('File Manager'), 'cDescription'=>t('All documents and images.')));
		$newC = $newFMSearch;
		$newC2 = Page::getByPath('/dashboard/file_manager/sets');

		//Hide the old file manager
		$oldFM = Page::getByPath('/dashboard/files');
		$oldFM->setAttribute('exclude_nav', 1);
		$oldFM->movePageDisplayOrderToBottom();
		$oldFM->update(array('cName'=>t('Legacy File Manager')));

		//Un-star old file manager and sets page, star new pages
		$c = Page::getByPath('/dashboard/files/search');
		$c2 = Page::getByPath('/dashboard/files/sets');
		$c3 = Page::getByPath('/dashboard/files');
		$ish = Loader::helper('concrete/interface');
		$ish->clearInterfaceItemsCache();

		$u = new User();
		$qn = ConcreteDashboardMenu::getMine();
		if ($qn->contains($c)) $qn->remove($c);
		if ($qn->contains($c2)) $qn->remove($c2);
		if ($qn->contains($c3)) $qn->remove($c3);
		if (!$qn->contains($newC)) $qn->add($newC);
		if (!$qn->contains($newC2)) $qn->add($newC2);

		$u->saveConfig('QUICK_NAV_BOOKMARKS', serialize($qn));
    }

    public function upgrade() {
        parent::upgrade();
		$this->installSinglePages($this);
    }

	private function installSinglePages($pkg){
		foreach($this->singlePages as $path => $attributes) {
			$page = Page::getByPath($path);
			if((!is_object($page)) || $page->isError()) $page = SinglePage::createSinglePage($path, $pkg);

			foreach($attributes as $handle => $val){
				$ak = CollectionAttributeKey::getByHandle($handle);
				if($ak instanceof AttributeKey){
					$page->setAttribute($handle, $val);
				}
			}
		}
	}

	public function uninstall(){
		$ish = Loader::helper('concrete/interface');
		$ish->clearInterfaceItemsCache();
		$u = new User();
		$qn = ConcreteDashboardMenu::getMine();

		//Put that thing back where it came from or so help me!
		$oldFM = Page::getByPath('/dashboard/files');
		$oldFM->setAttribute('exclude_nav', 0);
		$oldFM->updateDisplayOrder(2, $oldFM->getCollectionID());
		$oldFM->update(array('cName'=>t('File Manager')));

		//Un-star "new" pages and re-star "old" pages
		//Necessary to un-star to avoid duplicate entries if re-installed
		$c = Page::getByPath('/dashboard/files/search');
		$c2 = Page::getByPath('/dashboard/files/sets');
		$newC = Page::getByPath('/dashboard/file_manager/search');
		$newC2 = Page::getByPath('/dashboard/file_manager/sets');

		if (!$qn->contains($c)) $qn->add($c);
		if (!$qn->contains($c2)) $qn->add($c2);
		if ($qn->contains($newC)) $qn->remove($newC);
		if ($qn->contains($newC2)) $qn->remove($newC2);

		$u->saveConfig('QUICK_NAV_BOOKMARKS', serialize($qn));

		//Run the parent class uninstaller
		parent::uninstall();
    }

	public function on_start(){
		//find a better way to do this
		$u = new User();
		if($u->isLoggedIn()){
			$hh = Loader::helper('html');
			$v = View::getInstance();
			$v->addFooterItem($hh->javascript('custom-fmpopup.js', 'legacy_updated_file_manager'));
			$v->addHeaderItem($hh->javascript('bootstrap.min.js', 'legacy_updated_file_manager'));
			$v->addHeaderItem($hh->javascript('dropzone.min.js', 'legacy_updated_file_manager'));
			$v->addHeaderItem($hh->css('dropzone.min.css', 'legacy_updated_file_manager'));
			$v->addHeaderItem($hh->css('bootstrap.modals.css', 'legacy_updated_file_manager'));
			$v->addHeaderItem($hh->css('dropzone-custom.css', 'legacy_updated_file_manager'));
		}
	}
}