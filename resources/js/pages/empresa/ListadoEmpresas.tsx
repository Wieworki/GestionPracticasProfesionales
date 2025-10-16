import React from 'react';
import { Link } from '@inertiajs/react';

interface Empresa {
    id: number;
    nombre: string;
    email: string;
    habilitado?: boolean;
    created_at: string;
}

interface Column {
    key: keyof Empresa | 'acciones';
    label: string;
}

interface Props {
    empresas: Empresa[];
    columns: Column[];
    showNewButton?: boolean;
}

export default function ListadoEmpresas({
    empresas,
    columns,
    showNewButton = false,
}: Props) {
    const formatValue = (empresa: Empresa, key: keyof Empresa | 'acciones') => {
        switch (key) {
            case 'created_at':
                return new Date(empresa.created_at).toLocaleDateString('es-AR');
            case 'habilitado':
                return empresa.habilitado ? (
                    <span className="text-green-600 font-medium">Sí</span>
                ) : (
                    <span className="text-red-600 font-medium">No</span>
                );
            case 'acciones':
                return (
                    <Link
                        href={`/empresas/${empresa.id}`}
                        className="text-blue-600 hover:underline"
                    >
                        Ver detalle
                    </Link>
                );
            default:
                return empresa[key];
        }
    };

    return (
        <div className="max-w-6xl mx-auto bg-white shadow rounded-xl p-8">
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-semibold text-blue-700">
                    Listado de empresas
                </h1>
                {showNewButton && (
                    <Link
                        href="/administrativo/empresa/create"
                        className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                    >
                        Nueva empresa
                    </Link>
                )}
            </div>

            <table className="w-full border-collapse">
                <thead>
                    <tr className="text-left border-b">
                        {columns.map((col) => (
                            <th key={col.key} className="p-3">
                                {col.label}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {empresas.map((empresa) => (
                        <tr key={empresa.id} className="border-b hover:bg-gray-50">
                            {columns.map((col) => (
                                <td key={col.key} className="p-3">
                                    {formatValue(empresa, col.key)}
                                </td>
                            ))}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
