import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import { useForm } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";
import { FunctionComponent, } from "react";
import { usePageProps } from '../../hooks';

type Props = {
	onSuccess: (response: any) => void;
	onError: (error: any) => void
}
const CustomerInfo: FunctionComponent<Props> = ({ onSuccess, onError }) => {
	const { cart: { customer_info } }: { cart: any } = usePageProps();

	const { data, setData, post, processing, errors, } = useForm({
		name: customer_info?.name || '',
		email: customer_info?.email || '',
		phone: customer_info?.phone || '',
		action: 'customer-info',
	});

	const onSubmit = (e) => {
		e.preventDefault();
		post('/checkout', {
			onSuccess: (response) => {
				onSuccess(response);
				// console.log(response, 'success');
			},
			onError: (error) => {
				onError(error);
				console.log(error, 'on errors');
			}
		})
	};

	return (
		<div className="flex justify-center md:grid-cols-2 w-full rounded-md p-1">
			<form onSubmit={onSubmit}
				autoComplete="true"
				className='w-full sm:max-w-lg p-4 shadow-md overflow-hidden rounded-md border-2 flex flex-col gap-y-4'>
				<div className="">
					{/* @ts-ignore */}
					<InputLabel htmlFor="name" value="Họ và tên khách hàng:" className="font-semibold text-white" />
					<TextInput
						// @ts-ignore
						id="name"
						type="text"
						name="name"
						value={data.name}
						className="mt-1 block w-full bg-transparent placeholder-white"
						onChange={(e) => setData('name', e.target.value)}
						placeholder='Enter Name'
					/>
					<InputError message={errors.name} className="mt-2" />
				</div>

				<div className="w-full">
					{/* @ts-ignore */}
					<InputLabel htmlFor="email" value="Địa chỉ email:" className="font-semibold text-white" />
					<TextInput
						// @ts-ignore
						id="email"
						type="email"
						name="email"
						value={data.email}
						className="mt-1 block w-full bg-transparent placeholder-white"
						autoComplete="name"
						isFocused={true}
						onChange={(e) => setData('email', e.target.value)}
						placeholder='Enter email'
					/>
					<InputError message={errors.email} className="mt-2" />
				</div>

				<div className="">
					{/* @ts-ignore */}
					<InputLabel htmlFor="phone" value="Số điện thoại:" className="font-semibold text-white" />
					<TextInput
						// @ts-ignore
						id="phone"
						type="tel"
						name="phone"
						value={data.phone}
						className="mt-1 block w-full bg-transparent placeholder-white"
						onChange={(e) => setData('phone', e.target.value)}
						placeholder='Enter phone'
					/>
					<InputError message={errors.phone} className="mt-2" />
				</div>

				<div className="flex items-center justify-end mt-4">
					{/* @ts-ignore */}
					<Button variant="gradient" className="ms-4" type="submit"
						disabled={processing || Object.keys(errors).length > 0 || !data.name || !data.email || !data.phone}>
						Tiếp theo
					</Button>
				</div>
			</form>
		</div>
	)
}

export default CustomerInfo;