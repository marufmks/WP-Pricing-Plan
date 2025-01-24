import React from 'react';

const Dashboard = () => {
    return (
        <div className="dashboard">
            <h2>Dashboard</h2>
            <div className="dashboard-stats">
                <div className="stat-card">
                    <h3>Total Plans</h3>
                    <p className="stat-number">0</p>
                </div>
                <div className="stat-card">
                    <h3>Active Plans</h3>
                    <p className="stat-number">0</p>
                </div>
                <div className="stat-card">
                    <h3>Total Features</h3>
                    <p className="stat-number">0</p>
                </div>
            </div>
        </div>
    );
};

export default Dashboard; 