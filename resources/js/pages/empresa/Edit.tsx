import React from "react";
import { useForm, Head, Link } from "@inertiajs/react";
import EmpresaLayout from "@/layouts/dashboard/EmpresaLayout";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import InputError from '@/components/input-error';

interface EmpresaProps {
    empresa: {
        nombre: string;
        email: string;
        cuit: string;
        descripcion: string;
        sitio_web: string;
    };
}

export default function Edit({ empresa }: EmpresaProps) {
    const { data, setData, patch, processing, errors } = useForm({
        nombre: empresa.nombre || "",
        email: empresa.email || "",
        cuit: empresa.cuit || "",
        descripcion: empresa.descripcion || "",
        sitio_web: empresa.sitio_web || "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(route("empresa.update"));
    };

    return (
        <EmpresaLayout nombre={empresa.nombre} habilitado={true}>
            <Head title="Modificar Datos - Empresa" />
            <div className="max-w-3xl mx-auto bg-white shadow rounded-xl p-8">
                <h1 className="text-2xl font-semibold text-blue-700 mb-6">
                    Modificar datos de la empresa
                </h1>

                <form onSubmit={submit} className="space-y-5">
                    <div>
                        <Label htmlFor="nombre">Nombre</Label>
                        <Input
                            id="nombre"
                            value={data.nombre}
                            onChange={(e) => setData("nombre", e.target.value)}
                        />
                        <InputError message={errors.nombre} />
                    </div>

                    <div>
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData("email", e.target.value)}
                        />
                        <InputError message={errors.email} />
                    </div>

                    <div>
                        <Label htmlFor="cuit">CUIT</Label>
                        <Input
                            id="cuit"
                            value={data.cuit}
                            onChange={(e) => setData("cuit", e.target.value)}
                        />
                        <InputError message={errors.cuit} />
                    </div>

                    <div>
                        <Label htmlFor="descripcion">Descripción</Label>
                        <Input
                            id="descripcion"
                            value={data.descripcion}
                            onChange={(e) => setData("descripcion", e.target.value)}
                        />
                        <InputError message={errors.descripcion} />
                    </div>

                    <div>
                        <Label htmlFor="sitio_web">Sitio Web</Label>
                        <Input
                            id="sitio_web"
                            value={data.sitio_web}
                            onChange={(e) => setData("sitio_web", e.target.value)}
                        />
                        <InputError message={errors.sitio_web} />
                    </div>

                    <div className="flex justify-between pt-6">
                        <Link
                            href={route("empresa.perfil")}
                            className="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100"
                        >
                            Volver
                        </Link>
                        <div className="flex gap-2">
                            <Button
                                type="submit"
                                disabled={processing}
                                className="bg-blue-600 hover:bg-blue-700 text-white"
                            >
                                Guardar cambios
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </EmpresaLayout>
    );
}
