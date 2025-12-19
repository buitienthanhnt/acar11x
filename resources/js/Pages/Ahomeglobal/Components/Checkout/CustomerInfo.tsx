import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import { useForm } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";
import axios from "axios";
import { FunctionComponent, useEffect } from "react";
import usePageProps from "../../hooks/usePageProps";

type Props = {
	onSuccess: (response: any) => void;
	onError: (error: any) => void
}
const CustomerInfo: FunctionComponent<Props> = ({ onSuccess, onError }) => {
	const {orderInfo} : {orderInfo: any} = usePageProps();

	const { data, setData, post, processing, errors, reset, setError } = useForm({
		name: orderInfo?.name ||'' ,
		email: orderInfo?.email ||'',
		phone: orderInfo?.phone || '',
		action: 'order-info',
	});

	const onSubmit = (e) => {
		e.preventDefault();
		post('/checkout', {
			onSuccess: (response) => {
				onSuccess(response);
				console.log(response, 'success');
			},
			onError: (error) => {
				onError(error);
				console.log(error, 'on errors');
			}
		})
		// e.preventDefault();
		// axios.post('/customer-info', data).then(res => {

		// }).catch(err => {
		// 	setError(err.response.data.errors);
		// })
	};

	return (
		<div className="flex justify-center md:grid-cols-2 w-full rounded-md p-1">
			<form onSubmit={onSubmit}
				autoComplete="true"
				className='w-full sm:max-w-lg p-4 shadow-md overflow-hidden rounded-md border-2 '>
				<div className="mt-4">
					<InputLabel htmlFor="name" value="ten khach hang" className="text-white font-semibold" />
					<TextInput
						id="name"
						type="text"
						name="name"
						value={data.name}
						className="mt-1 block w-full bg-transparent placeholder-gray-800"
						onChange={(e) => setData('name', e.target.value)}
						placeholder='Enter Name'
					/>
					<InputError message={errors.name} className="mt-2" />
				</div>

				<div className="w-full">
					<InputLabel htmlFor="email" value="Email" className="text-white font-semibold" />
					<TextInput
						id="email"
						type="email"
						name="email"
						value={data.email}
						className="mt-1 block w-full bg-transparent placeholder-gray-800"
						autoComplete="name"
						isFocused={true}
						onChange={(e) => setData('email', e.target.value)}
						placeholder='Enter email'
					/>
					<InputError message={errors.email} className="mt-2" />
				</div>

				<div className="mt-4">
					<InputLabel htmlFor="phone" value="so dien thoai" className="text-white font-semibold" />
					<TextInput
						id="phone"
						type="tel"
						name="phone"
						value={data.phone}
						className="mt-1 block w-full bg-transparent placeholder-gray-800"
						onChange={(e) => setData('phone', e.target.value)}
						placeholder='Enter phone'
					/>
					<InputError message={errors.phone} className="mt-2" />
				</div>

				<div className="flex items-center justify-end mt-4">
					<Button variant="gradient" className="ms-4" type="submit"
						disabled={processing || Object.keys(errors).length > 0 || !data.email || !data.name || !data.phone}>
						Next action
					</Button>
				</div>
			</form>
		</div>
	)
}

export default CustomerInfo;