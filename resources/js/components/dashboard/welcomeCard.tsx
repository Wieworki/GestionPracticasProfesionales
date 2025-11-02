import React from 'react';

interface Props {
    mensaje: string;
}

export default function WelcomeCard({ mensaje }: Props) {
    return (
        <div className="bg-white rounded-2xl shadow p-6 text-center">
            <h1 className="text-2xl font-semibold text-blue-800 mb-2">
                ¡Bienvenido!
            </h1>
            <p className="text-gray-600">
                {mensaje}
            </p>
        </div>
    );
}