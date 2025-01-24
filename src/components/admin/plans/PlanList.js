import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FiPlus, FiEdit2, FiTrash2, FiPackage, FiCalendar, FiToggleRight } from 'react-icons/fi';
import { __ } from '@wordpress/i18n';

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
            setError(__('Failed to fetch plans:', 'pricing-plan') + ' ' + err.message);
            setLoading(false);
            setPlans([]);
        }
    };

    const handleDelete = async (id) => {
        if (!window.confirm(__('Are you sure you want to delete this plan?', 'pricing-plan'))) {
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
            setError(__('Failed to delete plan:', 'pricing-plan') + ' ' + err.message);
        }
    };

    if (loading) {
        return (
            <div className="plans-loading">
                <div className="loading-spinner"></div>
                <p>{__('Loading plans...', 'pricing-plan')}</p>
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
                    <h1>{__('Pricing Plans', 'pricing-plan')}</h1>
                    <p className="plans-subtitle">{__('Manage your pricing plans and packages', 'pricing-plan')}</p>
                </div>
                <Link to="/add-new" className="add-plan-button">
                    <FiPlus size={20} />
                    <span>{__('Add New Plan', 'pricing-plan')}</span>
                </Link>
            </div>

            {plans.length === 0 ? (
                <div className="empty-plans">
                    <div className="empty-plans-content">
                        <FiPackage size={48} />
                        <h2>{__('No Plans Yet', 'pricing-plan')}</h2>
                        <p>{__('Create your first pricing plan to get started', 'pricing-plan')}</p>
                        <Link to="/add-new" className="add-plan-button">
                            <FiPlus size={20} />
                            <span>{__('Create Plan', 'pricing-plan')}</span>
                        </Link>
                    </div>
                </div>
            ) : (
                <div className="plans-grid">
                    {plans.map(plan => (
                        <div key={plan.id} className="plan-card">
                            <div className="plan-card-header">
                                <h2>{plan.name || __('Untitled Plan', 'pricing-plan')}</h2>
                                <span className={`plan-status status-${plan.status || 'draft'}`}>
                                    {plan.status || __('draft', 'pricing-plan')}
                                </span>
                            </div>
                            
                            <div className="plan-card-content">
                                <div className="plan-meta">
                                    <div className="meta-item">
                                        <FiPackage size={16} />
                                        <span>{plan.packages?.length || 0} {__('Packages', 'pricing-plan')}</span>
                                    </div>
                                    <div className="meta-item">
                                        <FiToggleRight size={16} />
                                        <span>{plan.type || __('single', 'pricing-plan')}</span>
                                    </div>
                                    <div className="meta-item">
                                        <FiCalendar size={16} />
                                        <span>{plan.created_at 
                                            ? new Date(plan.created_at).toLocaleDateString()
                                            : __('N/A', 'pricing-plan')
                                        }</span>
                                    </div>
                                </div>
                                
                                <p className="plan-description">
                                    {plan.description || __('No description provided', 'pricing-plan')}
                                </p>
                            </div>

                            <div className="plan-card-actions">
                                <Link to={`/edit/${plan.id}`} className="edit-button">
                                    <FiEdit2 size={16} />
                                    <span>{__('Edit', 'pricing-plan')}</span>
                                </Link>
                                <button 
                                    onClick={() => handleDelete(plan.id)}
                                    className="delete-button"
                                >
                                    <FiTrash2 size={16} />
                                    <span>{__('Delete', 'pricing-plan')}</span>
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