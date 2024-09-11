document.addEventListener('DOMContentLoaded', () => {
   const selectAllButton = document.getElementById('aly-select-all');
   const installSelectedButton = document.getElementById('aly-install-selected');
   const progressContainer = document.getElementById('aly-progress-container');

   // Event handler for "Select All" button
   selectAllButton.addEventListener('click', () => {
       document.querySelectorAll('.aly-plugin-checkbox').forEach(checkbox => checkbox.checked = true);
   });

   // Event handler for "Install Selected" button
   installSelectedButton.addEventListener('click', () => {
       const selectedPlugins = Array.from(document.querySelectorAll('.aly-plugin-checkbox:checked'))
           .map(checkbox => checkbox.dataset.slug);

       if (selectedPlugins.length === 0) {
           showNotice('warning', 'No plugins selected for installation.');
           return;
       }

       bulkInstallAndActivatePlugins(selectedPlugins);
   });

   // Function to handle bulk plugin installation and activation
   const bulkInstallAndActivatePlugins = async (slugs) => {
       let completed = 0;
       updateProgress(`0 / ${slugs.length} plugins processed.`, 0);

       try {
           const response = await fetchPlugin(slugs, 'aly_bulk_install_plugins');
           response.forEach((result) => {
               if (result.success) {
                   completed++;
                   updateProgress(`${completed} / ${slugs.length} plugins processed.`, Math.round((completed / slugs.length) * 100));
               } else {
                   showNotice('error', `Failed to process ${result.slug}: ${result.message}`);
               }
           });

           if (completed === slugs.length) {
            showNotice('success', 'All selected plugins have been processed successfully! <a href="javascript:window.location.reload();">Refresh</a>');
        }
       } catch (error) {
           console.error('Error:', error);
           showNotice('error', 'An error occurred while processing the plugins.');
       }
   };

   // Function to fetch plugin installation and activation status via AJAX
   const fetchPlugin = async (slugs, action) => {
       const ajaxUrl = alyPluginInstaller.ajaxUrl;
       const nonce = alyPluginInstaller.nonce;

       const response = await fetch(ajaxUrl, {
           method: 'POST',
           headers: {
               'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
           },
           body: new URLSearchParams({
               action: action,
               nonce: nonce,
               slugs: JSON.stringify(slugs),
           })
       });

       return response.json();
   };

   // Function to update the progress bar
   const updateProgress = (message, percentage) => {
       progressContainer.innerHTML = `
           <div style="border: 1px solid #ccc; width: 100%; margin-top: 10px;">
               <div style="width: ${percentage}%; background: #4caf50; height: 5px; transition: all 0.5s;"></div>
           </div>
           <p>${message}</p>
       `;
   };

   // Function to display notices
   const showNotice = (type, message) => {
       const noticeContainer = document.createElement('div');
       noticeContainer.className = `notice notice-${type} is-dismissible`;
       noticeContainer.innerHTML = `<p>${message}</p>`;
       document.querySelector('.wrap').prepend(noticeContainer);
   };
});
