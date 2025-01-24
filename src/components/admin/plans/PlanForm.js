import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

const PlanForm = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [loading, setLoading] = useState(id ? true : false);
    const [error, setError] = useState(null);
    const [formData, setFormData] = useState({
        name: '',
        type: 'single',
        description: '',
        status: 'draft',
        settings: {}
    });

    useEffect(() => {
        if (id) {
            fetchPlan();
        }
    }, [id]);

    const fetchPlan = async () => {
        try {
            const response = await fetch(`${pricingPlanData.apiUrl}/plans/${id}`, {
                headers: {
                    'X-WP-Nonce': pricingPlanData.nonce
                }
            });
            const data = await response.json();
            setFormData(data);
            setLoading(false);
        } catch (err) {
            setError('Failed to fetch plan');
            setLoading(false);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        try {
            const url = id 
                ? `${pricingPlanData.apiUrl}/plans/${id}`
                : `${pricingPlanData.apiUrl}/plans`;
                
            const response = await fetch(url, {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': pricingPlanData.nonce
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                throw new Error('Failed to save plan');
            }

            navigate('/plans');
        } catch (err) {
            setError(err.message);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div className="error-message">{error}</div>;

    return (
        <div className="wrap">
            <h1>{id ? 'Edit Plan' : 'Add New Plan'}</h1>
            
            <form onSubmit={handleSubmit} className="pricing-plan-form">
                <table className="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label htmlFor="name">Plan Name</label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    className="regular-text"
                                    required
                                />
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label htmlFor="type">Plan Type</label>
                            </th>
                            <td>
                                <select
                                    id="type"
                                    name="type"
                                    value={formData.type}
                                    onChange={handleChange}
                                >
                                    <option value="single">Single</option>
                                    <option value="tabbed">Tabbed</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label htmlFor="description">Description</label>
                            </th>
                            <td>
                                <textarea
                                    id="description"
                                    name="description"
                                    value={formData.description}
                                    onChange={handleChange}
                                    rows="4"
                                    className="large-text"
                                />
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label htmlFor="status">Status</label>
                            </th>
                            <td>
                                <select
                                    id="status"
                                    name="status"
                                    value={formData.status}
                                    onChange={handleChange}
                                >
                                    <option value="draft">Draft</option>
                                    <option value="publish">Published</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div className="submit-wrapper">
                    <button type="submit" className="button button-primary">
                        {id ? 'Update Plan' : 'Create Plan'}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default PlanForm; 