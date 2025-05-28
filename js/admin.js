$(document).ready(function() {
    // Sidebar Toggle
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });

    // Initialize Chart
    var ctx = document.getElementById('petChart').getContext('2d');
    var petChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Perros', 'Gatos', 'Conejos', 'Otros'],
            datasets: [{
                data: [45, 30, 15, 10],
                backgroundColor: [
                    '#3498db',
                    '#2ecc71',
                    '#f39c12',
                    '#e74c3c'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });

    // Add animation class to cards on scroll
    $(window).scroll(function() {
        $('.stat-card').each(function() {
            var cardPosition = $(this).offset().top;
            var scrollPosition = $(window).scrollTop() + $(window).height();

            if (scrollPosition > cardPosition) {
                $(this).addClass('animate__animated animate__fadeInUp');
            }
        });
    });
});