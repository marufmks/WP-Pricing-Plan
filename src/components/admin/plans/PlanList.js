import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FiPlus, FiEdit2, FiTrash2, FiPackage, FiCalendar, FiToggleRight } from 'react-icons/fi';

const PlanList = () => {
    const [plans, setPlans] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchPlans();
    }, []);

    const fetchPlans = async () => {
        try {
            const response = await fetch(`${pricingPlanData.apiUrl}/plans`, {
                headers: {
                    'X-WP-Nonce': pricingPlanData.nonce,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            setPlans(Array.isArray(data) ? data : []);
            setLoading(false);
        } catch (err) {
            console.error('Error fetching plans:', err);
            setError('Failed to fetch plans: ' + err.message);
            setLoading(false);
            setPlans([]);
        }
    };

    const handleDelete = async (id) => {
        if (!window.confirm('Are you sure you want to delete this plan?')) {
            return;
        }

        try {
            const response = await fetch(`${pricingPlanData.apiUrl}/plans/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-WP-Nonce': pricingPlanData.nonce,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            await fetchPlans();
        } catch (err) {
            console.error('Error deleting plan:', err);
            setError('Failed to delete plan: ' + err.message);
        }
    };

    if (loading) {
        return (
            <div className="plans-loading">
                <div className="loading-spinner"></div>
                <p>Loading plans...</p>
            </div>
        );
    }

    if (error) {
        return (
            <div className="notice notice-error">
                <p>{error}</p>
            </div>
        );
    }

    return (
        <div className="plans-container">
            <div className="plans-header">
                <div className="plans-title">
                    <h1>Pricing Plans</h1>
                    <p className="plans-subtitle">Manage your pricing plans and packages</p>
                </div>
                <Link to="/add-new" className="add-plan-button">
                    <FiPlus size={20} />
                    <span>Add New Plan</span>
                </Link>
            </div>

            {plans.length === 0 ? (
                <div className="empty-plans">
                    <div className="empty-plans-content">
                        <FiPackage size={48} />
                        <h2>No Plans Yet</h2>
                        <p>Create your first pricing plan to get started</p>
                        <Link to="/add-new" className="add-plan-button">
                            <FiPlus size={20} />
                            <span>Create Plan</span>
                        </Link>
                    </div>
                </div>
            ) : (
                <div className="plans-grid">
                    {plans.map(plan => (
                        <div key={plan.id} className="plan-card">
                            <div className="plan-card-header">
                                <h2>{plan.name || 'Untitled Plan'}</h2>
                                <span className={`plan-status status-${plan.status || 'draft'}`}>
                                    {plan.status || 'draft'}
                                </span>
                            </div>
                            
                            <div className="plan-card-content">
                                <div className="plan-meta">
                                    <div className="meta-item">
                                        <FiPackage size={16} />
                                        <span>{plan.packages?.length || 0} Packages</span>
                                    </div>
                                    <div className="meta-item">
                                        <FiToggleRight size={16} />
                                        <span>{plan.type || 'single'}</span>
                                    </div>
                                    <div className="meta-item">
                                        <FiCalendar size={16} />
                                        <span>{plan.created_at 
                                            ? new Date(plan.created_at).toLocaleDateString()
                                            : 'N/A'
                                        }</span>
                                    </div>
                                </div>
                                
                                <p className="plan-description">
                                    {plan.description || 'No description provided'}
                                </p>
                            </div>

                            <div className="plan-card-actions">
                                <Link to={`/edit/${plan.id}`} className="edit-button">
                                    <FiEdit2 size={16} />
                                    <span>Edit</span>
                                </Link>
                                <button 
                                    onClick={() => handleDelete(plan.id)}
                                    className="delete-button"
                                >
                                    <FiTrash2 size={16} />
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default PlanList; 