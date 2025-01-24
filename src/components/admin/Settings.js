import React from 'react';

const Settings = () => {
    return (
        <div className="settings">
            <h2>Settings</h2>
            <div className="settings-form">
                <div className="form-group">
                    <label>Currency Symbol</label>
                    <input type="text" defaultValue="$" />
                </div>
                <div className="form-group">
                    <label>Currency Position</label>
                    <select defaultValue="before">
                        <option value="before">Before Price</option>
                        <option value="after">After Price</option>
                    </select>
                </div>
                <div className="form-group">
                    <label>Default Duration</label>
                    <select defaultValue="monthly">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <button className="save-settings-btn">Save Settings</button>
            </div>
        </div>
    );
};

export default Settings; 