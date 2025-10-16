import React from 'react';
import EmpresasTable from '@/pages/empresa/ListadoEmpresas';
import EstudianteLayout from '@/layouts/dashboard/EstudianteLayout';

interface Props {
    estudiante: {
        nombre: string;
        habilitado: boolean;
    };
    empresas: {
        id: number;
        nombre: string;
        email: string;
        created_at: string;
    }[];
    showNewButton: boolean;
}

export default function VerEmpresas({ estudiante, empresas, showNewButton }: Props) {
    return (
        <EstudianteLayout nombre={estudiante.nombre}>
            <EmpresasTable 
                empresas={empresas} 
                showNewButton={showNewButton} 
                columns={[
                    { key: 'nombre', label: 'Nombre' },
                    { key: 'email', label: 'Email' },
                    { key: 'created_at', label: 'Fecha de registro' },
                    { key: 'acciones', label: 'Acciones' },
                ]}
            />
        </EstudianteLayout>
    );
}
