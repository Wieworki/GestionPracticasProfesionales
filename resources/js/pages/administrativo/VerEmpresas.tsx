import React from 'react';
import EmpresasTable from '@/pages/empresa/ListadoEmpresas';
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';

interface Props {
    administrativo: {
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

export default function VerEmpresas({ administrativo, empresas, showNewButton }: Props) {
    return (
        <AdministrativoLayout nombre={administrativo.nombre}>
            <EmpresasTable 
                empresas={empresas} 
                showNewButton={showNewButton} 
                    columns={[
                        { key: 'nombre', label: 'Nombre' },
                        { key: 'email', label: 'Email' },
                        { key: 'habilitado', label: 'Habilitado' },
                        { key: 'created_at', label: 'Fecha de registro' },
                        { key: 'acciones', label: 'Acciones' },
                    ]}
            />
        </AdministrativoLayout>
    );
}
