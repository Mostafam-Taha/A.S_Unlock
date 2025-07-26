document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.view-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const itemId = this.getAttribute('data-id');
            fetchItemDetails(itemId);
        });
    });

    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const itemId = this.getAttribute('data-id');
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                deleteItem(itemId);
            }
        });
    });

    document.querySelectorAll('.featured-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const itemId = this.closest('tr').getAttribute('data-id');
            toggleFeatured(itemId, this);
        });
    });

    document.getElementById('saveItem').addEventListener('click', saveItemChanges);
});

function fetchItemDetails(itemId) {
    fetch(`../api/get_item.php?id=${itemId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.item;
                document.getElementById('itemId').value = item.id;
                document.getElementById('itemName').value = item.name;
                document.getElementById('itemDescription').value = item.description;
                document.getElementById('isFeatured').checked = item.is_featured == 1;

                const imageContainer = document.getElementById('itemImageContainer');
                imageContainer.innerHTML = '';
                if (item.image_path) {
                    const img = document.createElement('img');
                    img.src = item.image_path;
                    img.style.maxWidth = '100%';
                    img.style.maxHeight = '200px';
                    imageContainer.appendChild(img);
                }

                const audioContainer = document.getElementById('itemAudioContainer');
                audioContainer.innerHTML = '';
                if (item.audio_path) {
                    const audio = document.createElement('audio');
                    audio.controls = true;
                    audio.src = item.audio_path;
                    audioContainer.appendChild(audio);
                }

                const modal = new bootstrap.Modal(document.getElementById('itemModal'));
                modal.show();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function saveItemChanges() {
    const formData = new FormData();
    formData.append('id', document.getElementById('itemId').value);
    formData.append('name', document.getElementById('itemName').value);
    formData.append('description', document.getElementById('itemDescription').value);
    formData.append('is_featured', document.getElementById('isFeatured').checked ? 1 : 0);
    
    const imageFile = document.getElementById('itemImage').files[0];
    if (imageFile) formData.append('image', imageFile);
    
    const audioFile = document.getElementById('itemAudio').files[0];
    if (audioFile) formData.append('audio', audioFile);

    fetch('../api/update_item.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم حفظ التغييرات بنجاح');
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteItem(itemId) {
    fetch(`../api/delete_item.php?id=${itemId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حذف العنصر بنجاح');
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function toggleFeatured(itemId, iconElement) {
    fetch(`../api/toggle_featured.php?id=${itemId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.is_featured) {
                    iconElement.classList.remove('bi-star');
                    iconElement.classList.add('bi-star-fill', 'text-warning');
                } else {
                    iconElement.classList.remove('bi-star-fill', 'text-warning');
                    iconElement.classList.add('bi-star');
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}