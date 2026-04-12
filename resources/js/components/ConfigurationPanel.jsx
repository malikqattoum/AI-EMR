import React, { useState, useEffect, useRef } from 'react';
import ClinicalMonitoringService from './ClinicalMonitoringService';

const ConfigurationPanel = () => {
    const [rules, setRules] = useState([]);
    const [loading, setLoading] = useState(true);
    const isMountedRef = useRef(true);

    useEffect(() => {
        isMountedRef.current = true;
        const fetchRules = async () => {
            try {
                const data = await ClinicalMonitoringService.getRules();
                if (isMountedRef.current) {
                    setRules(data);
                }
            } catch (error) {
                console.error('Error fetching rules:', error);
            } finally {
                if (isMountedRef.current) {
                    setLoading(false);
                }
            }
        };
        fetchRules();
        return () => {
            isMountedRef.current = false;
        };
    }, []);

    const handleToggle = async (rule) => {
        try {
            const updatedRule = { ...rule, is_active: !rule.is_active };
            await ClinicalMonitoringService.updateRule(rule.id, updatedRule);
            setRules(prev => prev.map(r => r.id === rule.id ? updatedRule : r));
        } catch (error) {
            console.error('Error toggling rule:', error);
            // Revert the toggle on error by re-fetching
            const data = await ClinicalMonitoringService.getRules();
            setRules(data);
        }
    };

    if (loading) return (
        <div className="flex items-center justify-center p-12">
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            <span className="ml-3 text-gray-500">Loading configuration...</span>
        </div>
    );

    return (
        <div className="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h2 className="text-2xl font-bold mb-6 text-gray-800">Alert Rule Configuration</h2>
            
            <div className="space-y-6">
                {rules.map(rule => (
                    <div key={rule.id} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 className="font-bold text-gray-900">{rule.name}</h3>
                            <p className="text-sm text-gray-600">
                                Algorithm: <span className="uppercase">{rule.algorithm_type}</span> | 
                                Severity: <span className="capitalize">{rule.severity}</span>
                            </p>
                        </div>
                        <div className="flex items-center gap-4">
                            <div className="text-right mr-4">
                                <p className="text-xs text-gray-500 uppercase font-bold">Thresholds</p>
                                <p className="text-sm font-mono">
                                    {rule.threshold_min !== null ? `>= ${rule.threshold_min}` : ''} 
                                    {rule.threshold_min !== null && rule.threshold_max !== null ? ' & ' : ''}
                                    {rule.threshold_max !== null ? `<= ${rule.threshold_max}` : ''}
                                </p>
                            </div>
                            <button 
                                onClick={() => handleToggle(rule)}
                                className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none ${
                                    rule.is_active ? 'bg-green-500' : 'bg-gray-300'
                                }`}
                            >
                                <span className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${
                                    rule.is_active ? 'translate-x-6' : 'translate-x-1'
                                }`} />
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default ConfigurationPanel;
