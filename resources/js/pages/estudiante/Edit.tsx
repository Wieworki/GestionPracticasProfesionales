import React from "react";
import { useForm, Head, Link } from "@inertiajs/react";
import EstudianteLayout from "@/layouts/dashboard/EstudianteLayout";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import InputError from '@/components/input-error';

interface EstudianteProps {
    estudiante: {
        nombre: string;
        apellido: string;
        email: string;
        dni: string;
        telefono: string;
    };
}

export default function Edit({ estudiante }: EstudianteProps) {
    const { data, setData, patch, processing, errors } = useForm({
        nombre: estudiante.nombre || "",
        apellido: estudiante.apellido || "",
        email: estudiante.email || "",
        dni: estudiante.dni || "",
        telefono: estudiante.telefono || "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(route("estudiante.update"));
    };

    return (
        <EstudianteLayout nombre={estudiante.nombre}>
            <Head title="Modificar Datos - Estudiante" />
            <div className="max-w-3xl mx-auto bg-white shadow rounded-xl p-8">
                <h1 className="text-2xl font-semibold text-blue-700 mb-6">
                    Modificar datos
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
                        <Label htmlFor="apellido">Apellido</Label>
                        <Input
                            id="apellido"
                            value={data.apellido}
                            onChange={(e) => setData("apellido", e.target.value)}
                        />
                        <InputError message={errors.apellido} />
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
                        <Label htmlFor="dni">DNI</Label>
                        <Input
                            id="dni"
                            value={data.dni}
                            onChange={(e) => setData("dni", e.target.value)}
                        />
                        <InputError message={errors.dni} />
                    </div>

                    <div>
                        <Label htmlFor="telefono">Telefono</Label>
                        <Input
                            id="telefono"
                            value={data.telefono}
                            onChange={(e) => setData("telefono", e.target.value)}
                        />
                        <InputError message={errors.telefono} />
                    </div>

                    <div className="flex justify-between pt-6">
                        <Link
                            href={route("estudiante.perfil")}
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
        </EstudianteLayout>
    );
}
