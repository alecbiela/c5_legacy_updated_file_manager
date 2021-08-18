//This file is required to get our custom file manager to pop up on the frontend
//Overrides the file manager launch sequence with our packaged tool URL
window.onload = function(){
    ccm_launchFileManager = function(filters) {
        $.fn.dialog.open({
            width: '90%',
            height: '70%',
            appendButtons: true,
            modal: false,
            href: "/index.php/tools/packages/legacy_updated_file_manager/files/search_dialog?ocID=" + CCM_CID + "&search=1" + filters,
            title: ccmi18n_filemanager.title
        });
    }
};
