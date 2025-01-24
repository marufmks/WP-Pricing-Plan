import React, { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const Settings = () => {
    const [settings, setSettings] = useState({
        currency_symbol: '$',
        currency_position: 'before',
        default_duration: 'monthly',
        enable_custom_css: false,
        custom_css: '',
    });
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);

    useEffect(() => {
        fetchSettings();
    }, []);

    const fetchSettings = async () => {
        try {
            const response = await fetch(`${pricingPlanData.apiUrl}/settings`, {
                headers: {
                    'X-WP-Nonce': pricingPlanData.nonce,
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            setSettings(data);
            setLoading(false);
        } catch (err) {
            console.error('Error fetching settings:', err);
            toast.error(__('Failed to load settings', 'pricing-plan'));
            setLoading(false);
        }
    };

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setSettings(prev => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSaving(true);

        try {
            const response = await fetch(`${pricingPlanData.apiUrl}/settings`, {
                method: 'PUT',
                headers: {
                    'X-WP-Nonce': pricingPlanData.nonce,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(settings)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            await response.json();
            toast.success(__('Settings saved successfully', 'pricing-plan'));
        } catch (err) {
            console.error('Error saving settings:', err);
            toast.error(__('Failed to save settings', 'pricing-plan'));
        } finally {
            setSaving(false);
        }
    };

    if (loading) {
        return (
            <div className="settings-loading">
                <div className="loading-spinner"></div>
                <p>{__('Loading settings...', 'pricing-plan')}</p>
            </div>
        );
    }

    return (
        <div className="wrap">
            <ToastContainer />
            <h1>{__('Settings', 'pricing-plan')}</h1>
            
            <form onSubmit={handleSubmit}>
                <table className="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label htmlFor="currency_symbol">{__('Currency Symbol', 'pricing-plan')}</label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="currency_symbol"
                                    name="currency_symbol"
                                    value={settings.currency_symbol}
                                    onChange={handleChange}
                                    placeholder={__('Enter currency symbol', 'pricing-plan')}
                                    className="regular-text"
                                />
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label htmlFor="currency_position">{__('Currency Position', 'pricing-plan')}</label>
                            </th>
                            <td>
                                <select
                                    id="currency_position"
                                    name="currency_position"
                                    value={settings.currency_position}
                                    onChange={handleChange}
                                >
                                    <option value="before">{__('Before Price', 'pricing-plan')}</option>
                                    <option value="after">{__('After Price', 'pricing-plan')}</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label htmlFor="default_duration">{__('Default Duration', 'pricing-plan')}</label>
                            </th>
                            <td>
                                <select
                                    id="default_duration"
                                    name="default_duration"
                                    value={settings.default_duration}
                                    onChange={handleChange}
                                >
                                    <option value="monthly">{__('Monthly', 'pricing-plan')}</option>
                                    <option value="yearly">{__('Yearly', 'pricing-plan')}</option>
                                    <option value="lifetime">{__('Lifetime', 'pricing-plan')}</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label htmlFor="enable_custom_css">{__('Custom CSS', 'pricing-plan')}</label>
                            </th>
                            <td>
                                <label className="enable-css-label">
                                    <input
                                        type="checkbox"
                                        id="enable_custom_css"
                                        name="enable_custom_css"
                                        checked={settings.enable_custom_css}
                                        onChange={handleChange}
                                    />
                                    {__('Enable custom CSS', 'pricing-plan')}
                                </label>

                                {settings.enable_custom_css && (
                                    <div style={{ marginTop: '1rem' }}>
                                        <textarea
                                            id="custom_css"
                                            name="custom_css"
                                            value={settings.custom_css}
                                            onChange={handleChange}
                                            rows="8"
                                            placeholder={__('/* Enter your custom CSS here */\n.pricing-plan {\n  /* your styles */\n}', 'pricing-plan')}
                                            className="large-text code"
                                        />
                                    </div>
                                )}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p className="submit">
                    <button 
                        type="submit" 
                        className="button button-primary"
                        disabled={saving}
                    >
                        {saving ? __('Saving...', 'pricing-plan') : __('Save Settings', 'pricing-plan')}
                    </button>
                </p>
            </form>
        </div>
    );
};

export default Settings; 