# Concrete5 Legacy Updated File Manager
A concrete5 legacy (version &lt;5.6) package that updates the core File Manager with modern technologies.

The main purpose of this package is to convert the file uploader within the File Manager from Adobe Flash (no longer supported) to Dropzone.js, the same framework used in more modern versions of Concrete5. However, certain supplementary code has also been updated to support this functionality.

## Notes/Precautions
Since this package overrides core Concrete5 functionality, and has not been tested with other custom code or packages that alter the File Manager, you should install it at your own risk. It is recommended to test installation in a development or preview environment prior to installing on any production website. This software is provided "as-is" without guaranteed support or warranty (although bug reports through GitHub are certainly appreciated). See below for installation and uninstallation notes.

## Installation
Download the .zip archive of the current package and insert into the "packages" directory of your website, then install the package through the dashboard.

On **installation**, the package will do the following:
1. Installs new dashboard pages for all pages under the "File Manager" menu (Search, Sets, Attributes, Add Set). The page names are the same, but the paths have changed from /dashboard/files/[page] to /dashboard/file_manager/[page].
2. Hides all existing core dashboard pages related to the File Manager (same 4 pages as above) and places them as the third item on the dashboard menu (in the same position as the core File Manager by default).
3. Un-stars the core pages and stars the custom pages. This is for positioning in the popover dashboard menu (when mousing over the "dashboard" button in the CMS toolbar).

On **uninstallation**, the package will do the following:
1. Un-stars and removes custom File Manager dashboard pages.
2. Re-stars and un-hides all core File Manager Pages.

## Found a bug?
Please report all bugs using GitHub's built-in [issue tracker](https://github.com/alecbiela/c5_legacy_updated_file_manager/issues).

### Licenses
* This project is licensed under the Apache 2.0 License. A copy of this license is included in the root folder of the repository.
* Dropzone.js is licensed under the MIT License. A copy of this license has been included in the same folder as "dropzone.min.js"
* Concrete5 is licensed under the MIT License.