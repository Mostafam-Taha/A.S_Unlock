$(document).ready(function() {
    function loadIconLinks() {
        $.ajax({
            url: '../public/get_public_links.php',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const linksContainer = $('.social-links');
                    linksContainer.empty();
                    
                    response.data.forEach(link => {
                        const listItem = `
                            <li>
                                <a href="${link.url}" target="_blank" title="${link.name}"> ${link.icon || '<i class="bi bi-link-45deg"></i>'}</a>
                            </li>
                        `;
                        linksContainer.append(listItem);
                    });
                }
            },
            error: function() {
                console.error('Error loading icons');
            }
        });
    }

    loadIconLinks();
});