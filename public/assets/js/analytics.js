/**
 * Ultra-Lightweight Analytics Tracker
 * Optimized for minimal performance impact
 * Version: 1.0.0 - Cache-friendly
 */
class AnalyticsTracker {
    constructor() {
        this.endpoint = '/api/analytics/track';
        this.guestEndpoint = '/api/analytics/guest-activity';
        this.tracked = false;
        this.guestTracked = false;
        this.queue = [];
        this.init();
    }

    init() {
        // Use requestIdleCallback for better performance
        if (window.requestIdleCallback) {
            requestIdleCallback(() => this.trackPageView(), { timeout: 2000 });
        } else {
            // Fallback for browsers without requestIdleCallback
            setTimeout(() => this.trackPageView(), 100);
        }
        
        // Batch process queue periodically
        setInterval(() => this.processQueue(), 5000);
        
        // Process queue on page unload
        window.addEventListener('beforeunload', () => this.processQueue(true));
    }

    processQueue(immediate = false) {
        if (this.queue.length === 0) return;
        
        const batch = this.queue.splice(0, 10); // Process up to 10 items
        
        if (immediate && navigator.sendBeacon) {
            // Use sendBeacon for reliable delivery on page unload
            navigator.sendBeacon('/api/analytics/batch-track', JSON.stringify({
                views: batch
            }));
        } else {
            // Regular fetch for normal processing
            fetch('/api/analytics/batch-track', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ views: batch })
            }).catch(() => {
                // Re-queue failed items (up to 3 retries)
                batch.forEach(item => {
                    if ((item.retries || 0) < 3) {
                        item.retries = (item.retries || 0) + 1;
                        this.queue.push(item);
                    }
                });
            });
        }
    }

    trackPageView() {
        // Prevent duplicate tracking
        if (this.tracked) return;
        this.tracked = true;

        // Server handles all filtering - just send the data
        const data = this.collectPageData();
        this.sendTracking(data);
        
        // Also track guest activity (primary method)
        this.trackGuestActivity();
    }

    trackGuestActivity() {
        // Prevent duplicate guest tracking
        if (this.guestTracked) return;
        this.guestTracked = true;

        // Server handles all filtering - just send the request
        fetch(this.guestEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                timestamp: new Date().toISOString(),
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
            })
        }).catch(() => {
            // Silently fail - server-side fallback will handle it
        });
    }

    collectPageData() {
        // Lazy load expensive operations
        const getMetadata = () => ({
            full_url: window.location.href,
            query_params: this.getQueryParams(),
            is_mobile: this.isMobileDevice(),
            browser: this.getBrowserName(),
            screen_resolution: `${screen.width}x${screen.height}`,
            viewport_size: `${window.innerWidth}x${window.innerHeight}`,
            timestamp: new Date().toISOString(),
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            connection: navigator.connection ? {
                effectiveType: navigator.connection.effectiveType,
                downlink: navigator.connection.downlink
            } : null
        });

        return {
            url: window.location.pathname,
            page_title: document.title || null,
            referrer: document.referrer || null,
            user_agent: navigator.userAgent || null,
            metadata: getMetadata()
        };
    }

    getQueryParams() {
        const params = {};
        const urlParams = new URLSearchParams(window.location.search);
        for (const [key, value] of urlParams) {
            params[key] = value;
        }
        return params;
    }

    isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    getBrowserName() {
        const userAgent = navigator.userAgent;
        
        if (userAgent.includes('Chrome') && !userAgent.includes('Edg')) return 'Chrome';
        if (userAgent.includes('Firefox')) return 'Firefox';
        if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) return 'Safari';
        if (userAgent.includes('Edg')) return 'Edge';
        if (userAgent.includes('Opera') || userAgent.includes('OPR')) return 'Opera';
        if (userAgent.includes('Trident')) return 'IE';
        
        return 'Other';
    }

    sendTracking(data) {
        // Use fetch with minimal error handling to avoid blocking
        fetch(this.endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        }).catch(() => {
            // Silently fail - don't impact user experience
            // Could optionally retry or use navigator.sendBeacon as fallback
        });
    }

    // Alternative method using navigator.sendBeacon for page unload tracking
    trackPageUnload() {
        if (navigator.sendBeacon) {
            const data = JSON.stringify({
                url: window.location.pathname,
                event: 'page_unload',
                timestamp: new Date().toISOString()
            });
            
            navigator.sendBeacon(this.endpoint, data);
        }
    }
}

// Initialize analytics tracker
const analytics = new AnalyticsTracker();

// Optional: Track page unload for session duration calculation
window.addEventListener('beforeunload', () => analytics.trackPageUnload());

// Export for manual tracking if needed
window.analyticsTracker = analytics;
