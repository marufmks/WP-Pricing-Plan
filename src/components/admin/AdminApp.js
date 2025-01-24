import React from 'react';
import { HashRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Sidebar from './Sidebar';
import Dashboard from './Dashboard';
import PlanList from './plans/PlanList';
import PlanForm from './plans/PlanForm';
import Settings from './Settings';
import './admin.css';

const AdminApp = () => {
    // Get the current page from URL
    const currentPage = window.location.search.match(/page=([^&]*)/)?.[1] || 'pricing-plan';
    
    // Map WordPress admin pages to routes
    const getInitialRoute = () => {
        switch (currentPage) {
            case 'pricing-plan':
                return '/dashboard';
            case 'pricing-plan-plans':
                return '/plans';
            case 'pricing-plan-add-new':
                return '/add-new';
            case 'pricing-plan-settings':
                return '/settings';
            default:
                return '/dashboard';
        }
    };

    return (
        <Router>
            <div className="admin-app">
                <Sidebar />
                <div className="content-area">
                    <Routes>
                        <Route path="/" element={<Navigate to={getInitialRoute()} replace />} />
                        <Route path="/dashboard" element={<Dashboard />} />
                        <Route path="/plans" element={<PlanList />} />
                        <Route path="/add-new" element={<PlanForm />} />
                        <Route path="/edit/:id" element={<PlanForm />} />
                        <Route path="/settings" element={<Settings />} />
                    </Routes>
                </div>
            </div>
        </Router>
    );
};

export default AdminApp;