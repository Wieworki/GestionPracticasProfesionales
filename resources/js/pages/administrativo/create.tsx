import React from 'react';
import { FormEventHandler } from 'react';
import { Input } from '@/components/ui/input';
import { LoaderCircle } from 'lucide-react';
import { Head, useForm } from '@inertiajs/react';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import InputError from '@/components/input-error';
import AuthLayout from '@/layouts/auth-layout';

type CreateAdministrativoForm = {
    nombre: string,
    apellido: string,
    email: string;
    password: string;
    password_confirmation: string;
};

export default function Home() {

    const { data, setData, post, processing, errors, reset } = useForm<Required<CreateAdministrativoForm>>({
        nombre: '',
        apellido: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('storeAdministrativo'));
    };

    return (

            <AuthLayout title="Nuevo administrativo" description="Formulario de creacion de nuevo usuario administrativo">
                <Head title="Nuevo administrativo" />

                <form className="flex flex-col gap-6" onSubmit={submit}>
                    <div className="grid gap-6">
                        <div className="grid gap-2">
                            <Label htmlFor="nombre">Nombre</Label>
                            <Input
                                id="nombre"
                                type="text"
                                required
                                autoFocus
                                tabIndex={1}
                                autoComplete="nombre"
                                value={data.nombre}
                                onChange={(e) => setData('nombre', e.target.value)}
                                disabled={processing}
                                placeholder="Nombre"
                            />
                            <InputError message={errors.nombre} className="mt-2" />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="apellido">Apellido</Label>
                            <Input
                                id="apellido"
                                type="text"
                                required
                                autoFocus
                                tabIndex={1}
                                autoComplete="apellido"
                                value={data.apellido}
                                onChange={(e) => setData('apellido', e.target.value)}
                                disabled={processing}
                                placeholder="Apellido"
                            />
                            <InputError message={errors.nombre} className="mt-2" />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="email">Email</Label>
                            <Input
                                id="email"
                                type="email"
                                required
                                tabIndex={3}
                                autoComplete="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                disabled={processing}
                                placeholder="email@example.com"
                            />
                            <InputError message={errors.email} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password">Password</Label>
                            <Input
                                id="password"
                                type="password"
                                required
                                tabIndex={4}
                                autoComplete="new-password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                disabled={processing}
                                placeholder="Password"
                            />
                            <InputError message={errors.password} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password_confirmation">Confimar password</Label>
                            <Input
                                id="password_confirmation"
                                type="password"
                                required
                                tabIndex={5}
                                autoComplete="new-password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                disabled={processing}
                                placeholder="Confirmar password"
                            />
                            <InputError message={errors.password_confirmation} />
                        </div>

                        <Button type="submit" className="mt-2 w-full" tabIndex={5} disabled={processing}>
                            {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                            Crear administrativo
                        </Button>
                    </div>

                </form>
            </AuthLayout>
    );
}