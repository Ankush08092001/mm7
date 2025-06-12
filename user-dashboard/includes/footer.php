        </main>
    </div>

    <script src="../js/navigation.js"></script>
    <script src="js/dashboard.js"></script>
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Notification dropdown
        document.querySelector('.notification-bell').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('notification-dropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            
            if (dropdown.style.display === 'block') {
                loadNotifications();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.notification-bell')) {
                document.getElementById('notification-dropdown').style.display = 'none';
            }
        });

        // Load notifications via AJAX
        function loadNotifications() {
            fetch('api/get_notifications.php')
                .then(response => response.json())
                .then(data => {
                    const dropdown = document.getElementById('notification-dropdown');
                    dropdown.innerHTML = data.notifications.map(notification => `
                        <div class="notification-item" onclick="markAsRead(${notification.id})">
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${notification.created_at}</div>
                        </div>
                    `).join('');
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        // Mark notification as read
        function markAsRead(notificationId) {
            fetch('api/mark_notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notification_id: notificationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }
    </script>
</body>
</html> 