import React, { useState, useEffect } from 'react';
import ClinicalMonitoringService from './ClinicalMonitoringService';

const AlertManagement = () => {
    const [alerts, setAlerts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [currentPage, setCurrentPage] = useState(1);
    const alertsPerPage = 10;

    useEffect(() => {
        let isMounted = true;
        let unsubscribe = null;

        const fetchAlerts = async () => {
            setLoading(true);
            setError(null);
            try {
                const data = await ClinicalMonitoringService.getAlerts();
                if (isMounted) {
                    setAlerts(data);
                }
            } catch (error) {
                console.error('Error fetching alerts:', error);
                if (isMounted) {
                    setError('Failed to load alerts. Please try again.');
                }
            } finally {
                if (isMounted) {
                    setLoading(false);
                }
            }
        };

        fetchAlerts();

        // Subscribe to real-time alerts with cleanup
        unsubscribe = ClinicalMonitoringService.subscribeToAlerts((newAlert) => {
            if (isMounted) {
                setAlerts(prev => [newAlert, ...prev]);
            }
        });

        // Cleanup on unmount
        return () => {
            isMounted = false;
            if (unsubscribe && typeof unsubscribe === 'function') {
                unsubscribe();
            }
        };
    }, []);

    const handleAcknowledge = async (id) => {
        try {
            await ClinicalMonitoringService.acknowledgeAlert(id);
            setAlerts(prev => prev.filter(a => a.id !== id));
        } catch (error) {
            console.error('Error acknowledging alert:', error);
            if (window.showNotification) {
                window.showNotification('Failed to acknowledge alert.', 'error');
            }
        }
    };

    const handleEscalate = async (alert) => {
        try {
            await ClinicalMonitoringService.escalateAlert(alert.id);
            if (window.showNotification) {
                window.showNotification(`Alert escalated for patient: ${alert.patient?.name || 'Unknown'}`, 'warning');
            }
            setAlerts(prev => prev.filter(a => a.id !== alert.id));
        } catch (error) {
            console.error('Error escalating alert:', error);
            if (window.showNotification) {
                window.showNotification('Failed to escalate alert.', 'error');
            }
        }
    };

    // Pagination logic
    const indexOfLastAlert = currentPage * alertsPerPage;
    const indexOfFirstAlert = indexOfLastAlert - alertsPerPage;
    const currentAlerts = alerts.slice(indexOfFirstAlert, indexOfLastAlert);
    const totalPages = Math.ceil(alerts.length / alertsPerPage);

    if (loading) {
        return (
            <div className="flex items-center justify-center p-12 bg-white rounded-xl shadow-sm border border-gray-100">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                <span className="ml-3 text-gray-500 font-medium">Loading alerts...</span>
            </div>
        );
    }

    if (error) {
        return (
            <div className="p-6 bg-red-50 border border-red-200 rounded-xl text-red-700">
                <p className="font-bold">Error Loading Alerts</p>
                <p className="text-sm mt-1">{error}</p>
                <button 
                    onClick={() => window.location.reload()}
                    className="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                >
                    Retry
                </button>
            </div>
        );
    }

    return (
        <div className="bg-white rounded-xl shadow-sm border border-gray-100">
            <div className="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 className="text-2xl font-bold text-gray-800">Clinical Alert Management</h2>
                <span className="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm font-bold">
                    {alerts.length} Active
                </span>
            </div>

            <div className="overflow-x-auto">
                <table className="w-full text-left">
                    <thead>
                        <tr className="border-b border-gray-100">
                            <th className="pb-3 px-4 font-semibold text-gray-600">Patient</th>
                            <th className="pb-3 px-4 font-semibold text-gray-600">Severity</th>
                            <th className="pb-3 px-4 font-semibold text-gray-600">Message</th>
                            <th className="pb-3 px-4 font-semibold text-gray-600">Time</th>
                            <th className="pb-3 px-4 font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-50">
                        {currentAlerts.length === 0 ? (
                            <tr>
                                <td colSpan="5" className="py-8 text-center text-gray-500 italic">
                                    All clear. No active clinical alerts.
                                </td>
                            </tr>
                        ) : (
                            currentAlerts.map(alert => (
                                <tr key={alert.id} className="hover:bg-gray-50 transition">
                                    <td className="py-4 px-4 font-medium text-gray-900">{alert.patient?.name || 'Unknown'}</td>
                                    <td className="py-4 px-4">
                                        <span className={`px-2 py-1 rounded text-xs font-bold uppercase ${
                                            alert.severity === 'red' || alert.severity === 'high' ? 'bg-red-100 text-red-700' :
                                            alert.severity === 'orange' || alert.severity === 'medium' ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700'
                                        }`}>
                                            {alert.severity || 'Unknown'}
                                        </span>
                                    </td>
                                    <td className="py-4 px-4 text-gray-700">{alert.message || 'No message'}</td>
                                    <td className="py-4 px-4 text-sm text-gray-500">
                                        {alert.triggered_at ? new Date(alert.triggered_at).toLocaleString() : 'N/A'}
                                    </td>
                                    <td className="py-4 px-4 text-right">
                                        <div className="flex justify-end gap-2">
                                            <button
                                                onClick={() => handleEscalate(alert)}
                                                className="px-3 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition shadow-sm text-sm font-medium"
                                            >
                                                Escalate
                                            </button>
                                            <button
                                                onClick={() => handleAcknowledge(alert.id)}
                                                className="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm text-sm font-medium"
                                            >
                                                Acknowledge
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>

            {/* Pagination */}
            {totalPages > 1 && (
                <div className="flex justify-between items-center p-4 border-t border-gray-100">
                    <p className="text-sm text-gray-500">
                        Showing {indexOfFirstAlert + 1}-{Math.min(indexOfLastAlert, alerts.length)} of {alerts.length} alerts
                    </p>
                    <div className="flex gap-2">
                        <button
                            onClick={() => setCurrentPage(prev => Math.max(1, prev - 1))}
                            disabled={currentPage === 1}
                            className="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                        >
                            Previous
                        </button>
                        <span className="px-3 py-1 text-sm text-gray-700">
                            Page {currentPage} of {totalPages}
                        </span>
                        <button
                            onClick={() => setCurrentPage(prev => Math.min(totalPages, prev + 1))}
                            disabled={currentPage === totalPages}
                            className="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                        >
                            Next
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
};

export default AlertManagement;
