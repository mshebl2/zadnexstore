import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { createIcons, icons } from 'lucide';

Alpine.plugin(collapse);

window.Alpine = Alpine;
window.lucide = { createIcons, icons };

// Global luxury toast notification system
Alpine.store('toast', {
    message: '',
    type: 'success', // 'success', 'error', 'info'
    show: false,
    timeout: null,

    notify(message, type = 'success') {
        clearTimeout(this.timeout);
        this.message = message;
        this.type = type;
        this.show = true;
        this.timeout = setTimeout(() => {
            this.show = false;
        }, 3200);
    }
});

// Global cart drawer state and reactive count
Alpine.store('cart', {
    isOpen: false,
    count: 0,
    subtotal: 0,
    items: [],
    loading: false,

    open() {
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
        this.fetchCart();
    },

    close() {
        this.isOpen = false;
        document.body.style.overflow = '';
    },

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    },

    async fetchCart() {
        try {
            const res = await fetch('/api/cart');
            if (res.ok) {
                const data = await res.json();
                this.count = data.count;
                this.subtotal = data.subtotal;
                this.items = data.items;
            }
        } catch (e) {
            console.error('Failed to fetch cart', e);
        }
    },

    async addItem(productId, variantId = null, quantity = 1) {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity })
            });
            const data = await res.json();
            if (res.ok) {
                this.count = data.count;
                this.subtotal = data.subtotal;
                this.items = data.items;
                Alpine.store('toast').notify(data.message || 'تمت إضافة المنتج إلى سلة التسوق بنجاح');
                this.open();
            } else {
                Alpine.store('toast').notify(data.message || 'تعذر إضافة المنتج', 'error');
            }
        } catch (e) {
            Alpine.store('toast').notify('حدث خطأ في الاتصال', 'error');
        } finally {
            this.loading = false;
        }
    },

    async updateQuantity(itemId, quantity) {
        if (quantity < 1) return;
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/api/cart/update/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity })
            });
            const data = await res.json();
            if (res.ok) {
                this.count = data.count;
                this.subtotal = data.subtotal;
                this.items = data.items;
            } else {
                Alpine.store('toast').notify(data.message || 'تعذر تحديث الكمية', 'error');
            }
        } catch (e) {
            console.error(e);
        } finally {
            this.loading = false;
        }
    },

    async removeItem(itemId) {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/api/cart/remove/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (res.ok) {
                this.count = data.count;
                this.subtotal = data.subtotal;
                this.items = data.items;
                Alpine.store('toast').notify('تم حذف المنتج من السلة');
            }
        } catch (e) {
            console.error(e);
        } finally {
            this.loading = false;
        }
    }
});

// Quick view modal store
Alpine.store('quickView', {
    isOpen: false,
    product: null,
    loading: false,

    async open(productId) {
        this.isOpen = true;
        this.loading = true;
        document.body.style.overflow = 'hidden';
        try {
            const res = await fetch(`/api/products/${productId}/quick-view`);
            if (res.ok) {
                this.product = await res.json();
            }
        } catch (e) {
            console.error(e);
        } finally {
            this.loading = false;
        }
    },

    close() {
        this.isOpen = false;
        this.product = null;
        document.body.style.overflow = '';
    }
});

// Fluid Smooth Scroll Reveal on Scroll
function initScrollReveal() {
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px'
    });

    document.querySelectorAll('.reveal-on-scroll').forEach(el => {
        observer.observe(el);
    });
}

// Global mobile menu drawer state
Alpine.store('mobileMenu', {
    isOpen: false,

    open() {
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
    },

    close() {
        this.isOpen = false;
        document.body.style.overflow = '';
    },

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
    Alpine.store('cart').fetchCart();
    initScrollReveal();
});

Alpine.start();
