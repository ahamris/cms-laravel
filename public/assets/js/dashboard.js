document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const periodSelector = document.getElementById('period-selector');
    const loadingSpinner = document.getElementById('loading-spinner');
    const tabContent = document.getElementById('tab-content');
    
    // Handle tab clicks
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.getAttribute('data-tab');
            const period = periodSelector.value;
            loadTab(tabId, period);
        });
    });
    
    // Handle period changes
    periodSelector.addEventListener('change', function() {
        const activeTab = document.querySelector('.tab-link.border-blue-500');
        if (activeTab) {
            const tabId = activeTab.getAttribute('data-tab');
            loadTab(tabId, this.value);
        }
    });
    
    // Function to load tab content via AJAX
    function loadTab(tabId, period) {
        // Show loading spinner
        loadingSpinner.classList.remove('hidden');
        tabContent.classList.add('opacity-50');
        
        // Update active tab UI
        tabLinks.forEach(link => {
            if (link.getAttribute('data-tab') === tabId) {
                link.classList.add('border-blue-500', 'text-blue-600');
                link.classList.remove('border-transparent', 'text-gray-500');
            } else {
                link.classList.remove('border-blue-500', 'text-blue-600');
                link.classList.add('border-transparent', 'text-gray-500');
            }
        });
        
        // Update URL without page reload
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabId);
        url.searchParams.set('period', period);
        window.history.pushState({}, '', url);
        
        // Make AJAX request
        fetch(`/admin/dashboard?tab=${tabId}&period=${period}&ajax=1`)
            .then(response => response.text())
            .then(html => {
                // Create a temporary container to parse the response
                const temp = document.createElement('div');
                temp.innerHTML = html;
                
                // Find the tab content in the response
                const newContent = temp.querySelector(`#${tabId}-tab`);
                if (newContent) {
                    // Update the tab content
                    const currentTab = document.querySelector(`#${tabId}-tab`);
                    if (currentTab) {
                        currentTab.outerHTML = newContent.outerHTML;
                    } else {
                        // If tab doesn't exist yet, add it
                        tabContent.insertAdjacentHTML('beforeend', newContent.outerHTML);
                    }
                }
                
                // Hide loading spinner
                loadingSpinner.classList.add('hidden');
                tabContent.classList.remove('opacity-50');
                
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Show the active tab content
                const activeTabContent = document.querySelector(`#${tabId}-tab`);
                if (activeTabContent) {
                    activeTabContent.classList.add('active');
                }
                
                // Reinitialize any necessary plugins or event listeners
                if (typeof initApexCharts === 'function') {
                    initApexCharts();
                }
            })
            .catch(error => {
                console.error('Error loading tab:', error);
                loadingSpinner.classList.add('hidden');
                tabContent.classList.remove('opacity-50');
                alert('Error loading content. Please try again.');
            });
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'overview';
        const period = urlParams.get('period') || 'today';
        
        // Update period selector
        if (periodSelector) {
            periodSelector.value = period;
        }
        
        // Load the tab
        loadTab(tab, period);
    });
    
    // Initialize ApexCharts
    function initApexCharts() {
        const chartEl = document.querySelector("#analytics-chart");
        if (chartEl && typeof ApexCharts !== 'undefined') {
            // Destroy existing chart if it exists
            if (window.analyticsChart) {
                window.analyticsChart.destroy();
            }
            
            // Get chart data from the DOM
            const hours = JSON.parse(chartEl.getAttribute('data-hours') || '[]');
            
            if (hours && hours.length > 0) {
                const options = {
                    series: [
                        {
                            name: 'Visitors',
                            data: hours.map(hour => hour.visitors || 0),
                            color: '#3B82F6'
                        },
                        {
                            name: 'Pageviews',
                            data: hours.map(hour => hour.pageviews || 0),
                            color: '#EF4444'
                        }
                    ],
                    chart: {
                        height: '100%',
                        type: 'area',
                        fontFamily: 'Inter, sans-serif',
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: {
                        categories: hours.map(hour => hour.hour || ''),
                        labels: {
                            style: { colors: '#6B7280', fontSize: '12px' }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#6B7280', fontSize: '12px' }
                        },
                        min: 0,
                        max: Math.max(...hours.map(h => Math.max(h.visitors || 0, h.pageviews || 0))) * 1.1,
                        tickAmount: 5
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.6,
                            opacityTo: 0.1,
                            stops: [0, 90, 100]
                        }
                    },
                    grid: {
                        borderColor: '#F3F4F6',
                        strokeDashArray: 3,
                        yaxis: { lines: { show: true } }
                    },
                    tooltip: {
                        x: { format: 'HH:mm' },
                        style: { fontSize: '12px', fontFamily: 'Inter, sans-serif' },
                        y: { formatter: value => value + (value === 1 ? ' view' : ' views') }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '14px',
                        itemMargin: { horizontal: 16 },
                        markers: { radius: 12, offsetX: -4 }
                    }
                };
                
                window.analyticsChart = new ApexCharts(chartEl, options);
                window.analyticsChart.render();
            }
        }
    }
    
    // Initialize the page
    if (document.querySelector('.tab-link.border-blue-500')) {
        const activeTab = document.querySelector('.tab-link.border-blue-500').getAttribute('data-tab');
        const period = periodSelector ? periodSelector.value : 'today';
        loadTab(activeTab, period);
    }
    
    // Make initApexCharts available globally
    window.initApexCharts = initApexCharts;
    
    // Initialize charts on page load
    if (typeof ApexCharts !== 'undefined') {
        initApexCharts();
    }
});
