import { createRoot } from '@wordpress/element';
import React from 'react';
import './style.css';
import AdminApp from './components/admin/AdminApp';
const adminRoot = document.getElementById('pricing-plan-admin-root');
if (adminRoot) {
    const root = createRoot(adminRoot);
    root.render(
        <div className="pricing-plan-admin-wrapper">
            <AdminApp />
        </div>
    );
} 