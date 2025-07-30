<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Primary -->
    <meta name="title" content="A.S UNLOCK" />
    <meta name="description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://as_unlock.ct.ws" />
    <meta property="og:title" content="A.S UNLOCK" />
    <meta property="og:description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="og:image" content="https://as_unlock.ct.ws/" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://as_unlock.ct.ws/assets/image/favicon.ico" />
    <meta property="twitter:title" content="A.S UNLOCK" />
    <meta property="twitter:description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="twitter:image" content="https://as_unlock.ct.ws/assets/image/favicon.ico" />

    <!-- Links -->
    <link rel="icon" type="image/x-icon" href="../assets/image/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/chart.css">
    <link rel="stylesheet" href="../assets/css/crypto.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">

    <title>Dashboard</title>
</head>

<body>
    <!-- Header -->
    <header class="header" id="mainHeader">
        <div class="logo"><img src="../assets/image/favicon.ico" alt="Not Found Logo" loading="lazy">
            <h1>A.S_UNLOCK</h1>
        </div>
        <nav class="navber">
            <div class="un-order-list-sect">
                <span class="un-title">التقارير</span>
                <ul class="sect">
                    <li><a href="dashboard.php">نظرة عامة</a></li>
                    <li><a href="crypto.php">أدارة مالية</a></li>
                </ul>
            </div>
            <div class="un-order-list-app">
                <span class="un-title">التطبيقات</span>
                <ul class="app">
                    <li><a href="orders.php">الطلبات</a></li>
                    <li><a href="add_links.php">أدارة اللينكات</a></li>
                </ul>
            </div>
            <div class="un-order-list-page">
                <span class="un-title">الصفحات</span>
                <ul class="page">
                    <li><a href="./users.php">المستخدمين</a></li>
                    <li><a href="team_administrator.php">الموظفين</a></li>
                    <li><a href="add_team.php">ادارة الفريق index</a></li>
                    <li><a href="applications.php">اضافة وظيفة</a></li>
                    <li><a href="bouquets.php">الباقات</a></li>
                    <li><a href="products.php">المنتجات</a></li>
                    <li><a href="review-costm.php">اراء العملاء</a></li>
                    <li><a href="download.php">تحميلات</a></li>
                    <li><a href="#">الضمان</a></li>
                    <li><a href="#">الأسئلة الشائعة</a></li>
                </ul>
            </div>
            <div class="un-order-list-spp">
                <span class="un-title">الدعم</span>
                <ul class="spp">
                    <li><a href="#">التواصل مع الدعم</a></li>
                    <li><a href="#">ارشادات عمل A.S...</a></li>
                    <li><a href="#">سياسة mostafamtaha</a></li>
                    <li><a href="#">خدمة مع بعد البيع</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Body Dashboard -->
    <!-- Header - Top Short Cut -->
    <main class="main-content">
        <div class="header-top">
            <div class="ht-sei">
                <div class="flex-pro-net">
                    <div class="logo-profile"><img src="../assets/image/favicon.ico" alt="Not Image Profile" loading="lazy" id="profileImage"></div>
                    <div class="menu-profile">
                        <ul>
                            <li><a href="#">الملف الشخصي <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" /></svg></a></li>
                            <li><a href="#">تحليل<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" /></svg></a></li>
                        </ul>
                        <hr>
                        <ul>
                            <li><a href="./logout.php">تسجيل الخروج</a></li>
                        </ul>
                    </div>
                    <ul class="qu">
                        <li class="dark-mode-toggle"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 0 8 1zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16" /></svg></a></li>
                        <li><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" /></svg></a></li>
                    </ul>
                </div>
                <div class="flex-se">
                    <ul>
                        <li>
                            <button class="toggle-menu" aria-label="Toggle Menu">
                                <i class="bi bi-text-indent-left"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Dashboard Body -->
        <!-- Section Body -->
        <!-- #Analytics -->
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
        }
        .stat-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            background: white;
        }
    </style>
    <div class="container py-5">
        <h1 style="text-align: right !important;" class="text-center mb-5">إحصائيات الموقع</h1>
        
        <!-- سريع الإطلاع على الإحصائيات -->
        <div class="row mb-4" id="quickStatsContainer">
            <!-- سيتم ملؤها بواسطة JavaScript -->
        </div>
        
        <div class="row chart-back">
            <!-- Line Chart -->
            <div class="col-12 col-lg-6">
                <div class="stat-card">
                    <h4 class="text-center">نمو المستخدمين (خطي)</h4>
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Bar Chart -->
            <div class="col-12 col-lg-6">
                <div class="stat-card">
                    <h4 class="text-center">المنتجات الأكثر مبيعاً (أعمدة)</h4>
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Doughnut Chart -->
            <div class="col-12 col-lg-6">
                <div class="stat-card">
                    <h4 class="text-center">حالة الطلبات (دونات)</h4>
                    <div class="chart-container">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Polar Area Chart -->
            <div class="col-12 col-lg-6">
                <div class="stat-card">
                    <h4 class="text-center">طرق الدفع (قطبي)</h4>
                    <div class="chart-container">
                        <canvas id="polarAreaChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Area Chart -->
            <div class="col-12">
                <div class="stat-card">
                    <h4 class="text-center">الإيرادات الشهرية (منطقة)</h4>
                    <div class="chart-container">
                        <canvas id="areaChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Heatmap Chart -->
            <div class="col-12">
                <div class="stat-card">
                    <h4 class="text-center">نشاط الطلبات حسب الساعة (خريطة حرارية)</h4>
                    <div class="chart-container">
                        <canvas id="heatmapChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!--  -->
    <div class="screen-size-warning">
        ⚠️ عذراً، الموقع لا يعمل بشكل صحيح على شاشات أصغر من 600px<br>
        الرجاء استخدام جهاز بشاشة أكبر أو تكبير نافذة المتصفح
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/dark-mode.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.querySelector('.toggle-menu');
            const header = document.querySelector('.header');
            const mainContent = document.querySelector('.main-content');

            const isHeaderClosed = localStorage.getItem('headerClosed') === 'true';

            if (isHeaderClosed) {
                header.classList.add('closed');
            } else {
                header.classList.remove('closed');
            }

            toggleBtn.addEventListener('click', function () {
                header.classList.toggle('closed');

                // حفظ الحالة في localStorage
                localStorage.setItem('headerClosed', header.classList.contains('closed'));
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const profileImage = document.getElementById('profileImage');
            const menuProfile = document.querySelector('.menu-profile');

            profileImage.addEventListener('click', function (e) {
                e.stopPropagation();
                menuProfile.classList.toggle('active');
            });

            document.addEventListener('click', function (e) {
                if (!menuProfile.contains(e.target) && e.target !== profileImage) {
                    menuProfile.classList.remove('active');
                }
            });
        });
    </script>
    <!-- JavaScript لإنشاء المخططات -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // جلب البيانات من الخادم
            fetch('stats.php')
                .then(response => response.json())
                .then(data => {
                    // عرض الإحصائيات السريعة
                    displayQuickStats(data.quickStats);
                    
                    // Line Chart - نمو المستخدمين
                    createLineChart(data.userGrowth);
                    
                    // Bar Chart - المنتجات الأكثر مبيعاً
                    createBarChart(data.topProducts);
                    
                    // Doughnut Chart - حالة الطلبات
                    createDoughnutChart(data.orderStatus);
                    
                    // Polar Area Chart - طرق الدفع
                    createPolarAreaChart(data.paymentMethods);
                    
                    // Area Chart - الإيرادات الشهرية
                    createAreaChart(data.monthlyRevenue);
                    
                    // Heatmap Chart - نشاط الطلبات حسب الساعة
                    createHeatmapChart(data.orderActivity);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
                
            function displayQuickStats(stats) {
                const container = document.getElementById('quickStatsContainer');
                
                // تأكد من أن جميع القيم موجودة وتعيين قيم افتراضية إذا لم تكن موجودة
                const total_users = stats.total_users || 0;
                const total_products = stats.total_products || 0;
                const total_orders = stats.total_orders || 0;
                let total_revenue = 0;
                
                if (stats.total_revenue !== null && stats.total_revenue !== undefined) {
                    total_revenue = parseFloat(stats.total_revenue).toFixed(2);
                }
                
                container.innerHTML = `
                    <div class="row mb-4" id="quickStatsContainer">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card text-center">
                                <div class="icon-container mb-3">
                                    <i class="fas fa-users fa-2x fa-lg text-primary"></i>
                                </div>
                                <h5>المستخدمون</h5>
                                <h4 class="text-primary">${total_users}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card text-center">
                                <div class="icon-container mb-3">
                                    <i class="fas fa-box-open fa-2x fa-lg text-success"></i>
                                </div>
                                <h5>المنتجات</h5>
                                <h4 class="text-success">${total_products}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card text-center">
                                <div class="icon-container mb-3">
                                    <i class="fas fa-shopping-cart fa-2x fa-lg text-info"></i>
                                </div>
                                <h5>الطلبات</h5>
                                <h4 class="text-info">${total_orders}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="stat-card text-center">
                                <div class="icon-container mb-3">
                                    <i class="fas fa-money-bill-wave fa-2x fa-lg text-warning"></i>
                                </div>
                                <h5>الإيرادات</h5>
                                <h4 class="text-warning">${total_revenue}</h4>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            function createLineChart(data) {
                const labels = data.map(item => item.month);
                const counts = data.map(item => item.count);
                
                const lineCtx = document.getElementById('lineChart').getContext('2d');
                const lineChart = new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'المستخدمون الجدد',
                            data: counts,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
            
            function createBarChart(data) {
                const labels = data.map(item => item.name);
                const sales = data.map(item => item.sales);
                
                const barCtx = document.getElementById('barChart').getContext('2d');
                const barChart = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'عدد المبيعات',
                            data: sales,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
            
            function createDoughnutChart(data) {
                const labels = data.map(item => item.status);
                const counts = data.map(item => item.count);
                
                const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
                const doughnutChart = new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: counts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
            
            function createPolarAreaChart(data) {
                const labels = data.map(item => item.payment_method);
                const counts = data.map(item => item.count);
                
                const polarAreaCtx = document.getElementById('polarAreaChart').getContext('2d');
                const polarAreaChart = new Chart(polarAreaCtx, {
                    type: 'polarArea',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: counts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
            
            function createAreaChart(data) {
                const labels = data.map(item => item.month);
                const revenues = data.map(item => item.revenue);
                
                const areaCtx = document.getElementById('areaChart').getContext('2d');
                const areaChart = new Chart(areaCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'الإيرادات',
                            data: revenues,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
            
            function createHeatmapChart(data) {
                // ننشئ مصفوفة تحتوي على جميع الساعات (0-23)
                const hours = Array.from({length: 24}, (_, i) => i);
                const hourCounts = {};
                
                // نملأ الكائن بقيم الصفر أولاً
                hours.forEach(hour => {
                    hourCounts[hour] = 0;
                });
                
                // نملأ الكائن بالقيم الفعلية من البيانات
                data.forEach(item => {
                    hourCounts[item.hour] = item.count;
                });
                
                // نحول الكائن إلى مصفوفة
                const counts = hours.map(hour => hourCounts[hour]);
                
                const heatmapCtx = document.getElementById('heatmapChart').getContext('2d');
                const heatmapChart = new Chart(heatmapCtx, {
                    type: 'bar',
                    data: {
                        labels: hours.map(h => `${h}:00`),
                        datasets: [{
                            label: 'عدد الطلبات',
                            data: counts,
                            backgroundColor: hours.map(hour => {
                                // نحدد اللون حسب عدد الطلبات
                                const count = hourCounts[hour];
                                if (count > 20) return 'rgba(255, 99, 132, 0.9)';
                                if (count > 10) return 'rgba(255, 159, 64, 0.8)';
                                if (count > 5) return 'rgba(255, 205, 86, 0.7)';
                                return 'rgba(75, 192, 192, 0.6)';
                            }),
                            borderColor: hours.map(hour => {
                                const count = hourCounts[hour];
                                if (count > 20) return 'rgba(255, 99, 132, 1)';
                                if (count > 10) return 'rgba(255, 159, 64, 1)';
                                if (count > 5) return 'rgba(255, 205, 86, 1)';
                                return 'rgba(75, 192, 192, 1)';
                            }),
                            borderWidth: 1
                        }]
                    },
                    // في كل دالة إنشاء مخطط، أضف هذه الخيارات
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom', // نقل وسيلة الإيضاح إلى الأسفل
                                labels: {
                                    boxWidth: 12,
                                    padding: 20,
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12 // حجم الخط حسب حجم الشاشة
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    }
                                }
                            },
                            y: {
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>