import React from 'react';
import { AlertCircle } from 'lucide-react';

export default function DisabledAccountNotice() {
    return (
        <div className="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-xl p-4 flex items-center space-x-3">
            <AlertCircle className="w-6 h-6 text-yellow-600" />
            <div>
                <h3 className="font-semibold">Cuenta pendiente de habilitación</h3>
                <p className="text-sm">
                    Tu cuenta aún no fue habilitada por un administrador. 
                    Por favor, espera la aprobación para acceder a todas las funcionalidades.
                </p>
            </div>
        </div>
    );
}