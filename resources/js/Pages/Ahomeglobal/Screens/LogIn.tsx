import { Head, Link, useForm } from "@inertiajs/react";
import { useEffect } from "react";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";
import Checkbox from "@/Components/Checkbox";
import BodyLayout from "../Layouts/BodyLayout";

const LogIn = ({ topMenu, status, canResetPassword }) => {
	const { data, setData, post, processing, errors, reset } = useForm({
		email: '',
		password: '',
		remember: false,
	});

	useEffect(() => {
		return () => {
			reset('password');
		};
	}, []);

	const submit = (e) => {
		e.preventDefault();

		post(route('login'), {
			onSuccess: (response) => { // trigger when errors not data
				console.log(response, 'success');
			},
			onError: (error) => {  // trigger when errors has data
				console.log(error, 'on errors');
			}
		});
	};
	return (
		<BodyLayout contentClass="md:max-w-none">
			<Head title="Login"></Head>
			<div className="flex-1 flex flex-col p-4 gap-y-1 justify-center items-center bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center">
				<form onSubmit={submit} autoComplete="true" className='w-full sm:max-w-md lg:max-w-2xl p-4 shadow-md overflow-hidden rounded-md border-2'>
					<div className="w-full">
						{/* @ts-ignore */}
						<InputLabel htmlFor="email" value="Email" className="text-white font-semibold" />
						<TextInput  // @ts-ignore
							id="email"
							type="email"
							name="email"
							value={data.email}
							className="mt-1 block w-full bg-transparent"
							autoComplete="username"
							isFocused={true}
							onChange={(e) => setData('email', e.target.value)}
							placeholder='Enter email'
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
							className="mt-1 block w-full bg-transparent"
							autoComplete="current-password"
							onChange={(e) => setData('password', e.target.value)}
							placeholder='Enter password'
						/>
						<InputError message={errors.password} className="mt-2" />
					</div>
					<div className="block mt-4">
						<label className="flex items-center">
							<Checkbox
								name="remember"
								checked={data.remember}
								onChange={(e) => setData('remember', e.target.checked)}
							/>
							<span className="ms-2 text-sm text-gray-600">Ghi nhớ!</span>
						</label>
					</div>
					<div className="flex items-center justify-end mt-4">
						{(
							<Link
								href={'/account-create'}
								className="underline text-md text-gray-100 hover:text-green-900"
							>
								Tạo tài khoản!
							</Link>
						)}
						<PrimaryButton className="ms-4" disabled={processing}>
							Đăng nhập
						</PrimaryButton>
					</div>
				</form>
			</div>
		</BodyLayout>
	)
}

export default LogIn;