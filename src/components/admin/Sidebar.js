import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { 
    FiGrid, 
    FiDollarSign, 
    FiSettings, 
    FiList, 
    FiPlusCircle,
    FiChevronDown
} from 'react-icons/fi';

const Sidebar = () => {
    const location = useLocation();
    const currentPath = location.pathname;
    const [isPlansExpanded, setIsPlansExpanded] = useState(
        currentPath.includes('/plans') || currentPath.includes('/add-new') || currentPath.includes('/edit')
    );

    const menuItems = [
        { 
            id: 'dashboard', 
            label: 'Dashboard', 
            icon: <FiGrid size={18} />, 
            path: '/dashboard' 
        },
        {
            id: 'plans',
            label: 'Plans',
            icon: <FiDollarSign size={18} />,
            path: '/plans',
            submenu: [
                { id: 'all-plans', label: 'All Plans', icon: <FiList size={16} />, path: '/plans' },
                { id: 'add-new', label: 'Add New', icon: <FiPlusCircle size={16} />, path: '/add-new' }
            ]
        },
        { 
            id: 'settings', 
            label: 'Settings', 
            icon: <FiSettings size={18} />, 
            path: '/settings' 
        },
    ];

    const handleMenuClick = (menuItem) => {
        if (menuItem.id === 'plans') {
            setIsPlansExpanded(!isPlansExpanded);
        }
    };

    return (
        <div className="sidebar">
            <div className="plugin-header">
                <div className="logo-wrapper">
                    <span className="logo-icon">💎</span>
                    <h2>Pricing Plan</h2>
                </div>
            </div>
            <nav className="sidebar-nav">
                {menuItems.map((item) => (
                    <div key={item.id} className="menu-item-wrapper">
                        <Link
                            to={item.path}
                            className={`nav-item ${
                                currentPath === item.path || 
                                (item.id === 'plans' && (currentPath.includes('/add-new') || currentPath.includes('/edit'))) 
                                ? 'active' : ''
                            }`}
                            onClick={() => handleMenuClick(item)}
                        >
                            <span className="icon">{item.icon}</span>
                            <span className="label">{item.label}</span>
                            {item.submenu && (
                                <FiChevronDown 
                                    className={`submenu-arrow ${isPlansExpanded ? 'expanded' : ''}`}
                                    size={16}
                                />
                            )}
                        </Link>
                        {item.submenu && isPlansExpanded && (
                            <div className="submenu">
                                {item.submenu.map(subItem => (
                                    <Link
                                        key={subItem.id}
                                        to={subItem.path}
                                        className={`nav-item submenu-item ${
                                            currentPath === subItem.path || 
                                            (subItem.path === '/add-new' && currentPath.includes('/edit'))
                                            ? 'active' : ''
                                        }`}
                                    >
                                        <span className="icon">{subItem.icon}</span>
                                        <span className="label">{subItem.label}</span>
                                    </Link>
                                ))}
                            </div>
                        )}
                    </div>
                ))}
            </nav>
            <div className="sidebar-footer">
                <div className="version">Version 1.0.0</div>
            </div>
        </div>
    );
};

export default Sidebar; 