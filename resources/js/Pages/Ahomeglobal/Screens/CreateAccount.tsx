import { Head, useForm } from "@inertiajs/react";
import { useEffect } from "react";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";
import BodyLayout from "../Layouts/BodyLayout";

const CreateAccount = ({ topMenu, status, canResetPassword }) => {
	const { data, setData, post, processing, errors, reset } = useForm({
		name: '',
		email: '',
		password: '',
		password_confirmation: '',
	});

	useEffect(() => {
		return () => {
			reset('password', 'password_confirmation');
		};
	}, []);

	const submit = (e) => {
		e.preventDefault();

		post(route('register'));
	};

	return (
		<BodyLayout>
			<Head title="Login"></Head>
			<div className="bg-red-100 flex-1 flex flex-col p-4 gap-y-1 justify-center items-center bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center">
				<form onSubmit={submit} autoComplete="true" className='w-full sm:max-w-md p-4 shadow-md overflow-hidden rounded-md border-2'>
					<div>
						{/* @ts-ignore */}
						<InputLabel htmlFor="name" value="Name" className="text-white font-semibold" />

						<TextInput
							// @ts-ignore
							id="name"
							name="name"
							value={data.name}
							className="mt-1 block w-full bg-transparent text-white"
							autoComplete="name"
							isFocused={true}
							onChange={(e) => setData('name', e.target.value)}
							required
						/>

						<InputError message={errors.name} className="mt-2" />
					</div>

					<div className="w-full">
						{/* @ts-ignore */}
						<InputLabel htmlFor="email" value="Email" className="text-white font-semibold" />
						<TextInput // @ts-ignore
							id="email"
							type="email"
							name="email"
							value={data.email}
							className="mt-1 block w-full bg-transparent text-white"
							autoComplete="username"
							isFocused={true}
							onChange={(e) => setData('email', e.target.value)}
							placeholder='Enter email'
							required
						/>
						<InputError message={errors.email} className="mt-2" />
					</div>

					<div className="mt-4">
						{/* @ts-ignore */}
						<InputLabel htmlFor="password" value="Mật khẩu" className="text-white font-semibold" />
						<TextInput  // @ts-ignore
							id="password"
							type="password"
							name="password"
							value={data.password}
							className="mt-1 block w-full bg-transparent text-white"
							autoComplete="current-password"
							onChange={(e) => setData('password', e.target.value)}
							placeholder='Enter password'
							required
						/>
						<InputError message={errors.password} className="mt-2" />
					</div>

					<div className="mt-4">
						{/* @ts-ignore */}
						<InputLabel htmlFor="password_confirmation" value="Xác nhận mật khẩu" className="text-white font-semibold" />

						<TextInput  // @ts-ignore
							id="password_confirmation"
							type="password"
							name="password_confirmation"
							value={data.password_confirmation}
							className="mt-1 block w-full bg-transparent text-white"
							autoComplete="new-password"
							onChange={(e) => setData('password_confirmation', e.target.value)}
							placeholder='Enter confirm password'
							required
						/>

						<InputError message={errors.password_confirmation} className="mt-2" />
					</div>

					<div className="flex items-center justify-end mt-4">
						<PrimaryButton className="ms-4" disabled={processing}>
							Đăng ký
						</PrimaryButton>
					</div>
				</form>
			</div>
		</BodyLayout>
	)
}

export default CreateAccount;