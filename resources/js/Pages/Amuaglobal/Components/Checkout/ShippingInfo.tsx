import { useMemo } from "react";
import { useForm } from "@inertiajs/react";
import Checkbox from "@/Components/Checkbox";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import { Button } from "@material-tailwind/react";
import { usePageProps } from "@/Pages/Amuaglobal/hooks";
import { formatCurrency } from "@/Pages/Amuaglobal/Helper/NumberHelper";

const ShippingInfo = ({ onSuccess, onError, }) => {
	const { cart } = usePageProps();

	const { data, post, processing, errors, setData } = useForm({
		method: cart.shipping_method?.key,
		same_as_customer: true,
		name: cart.shipping_address?.name || '',
		phone: cart.shipping_address?.phone || '',
		location: cart.shipping_address?.location || '',
		action: 'shipping-info',
	})

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
	};

	const isComplete = useMemo(() => {
		if (!data.method || !data.location || Object.keys(errors).length > 0 || processing) {
			return false;
		}
		if (data.same_as_customer) {
			return true;
		}
		return data.name && data.phone
	}, [data.location, data.method, data.name, data.phone, data.same_as_customer, errors, processing]);

	return (
		<div className="flex justify-center md:grid-cols-2 w-full rounded-md p-1">
			<form onSubmit={onSubmit}
				autoComplete="true"
				className='w-full sm:max-w-lg p-4 shadow-md overflow-hidden rounded-md border-2 flex flex-col gap-y-4'>
				<ShippingMethod selected={data.method} onSelect={(method) => setData('method', method)}></ShippingMethod>
				<div className="block">
					<label className="flex items-center">
						<Checkbox
							name="remember"
							checked={data.same_as_customer}
							onChange={(e) => setData('same_as_customer', e.target.checked)}
						/>
						<span className="ms-2 text-sm text-white">Người mua trực tiếp nhận hàng</span>
					</label>
				</div>
				{!data.same_as_customer && <div className="">
					{/* @ts-ignore */}
					<InputLabel htmlFor="name" value="Người nhận:" className="font-semibold text-white" />
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
				</div>}

				{!data.same_as_customer && <div className="">
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
				</div>}

				<div className="">
					{/* @ts-ignore */}
					<InputLabel htmlFor="location" value="Địa chỉ nhận hàng:" className="font-semibold text-white" />
					<TextInput
						// @ts-ignore
						id="location"
						type="text"
						name="data.location"
						value={data.location}
						className="mt-1 block w-full bg-transparent placeholder-white"
						onChange={(e) => setData('location', e.target.value)}
						placeholder='Enter Arrival address'
					/>
					<InputError message={errors.location} className="mt-2" />
				</div>

				<div className="flex items-center justify-end mt-4">
					{/* @ts-ignore */}
					<Button variant="gradient" className="ms-4" type="submit"
						disabled={!isComplete}>
						Tiếp theo
					</Button>
				</div>
			</form>
		</div>
	)
}

const ShippingMethod = ({ selected, onSelect }) => {
	const { shipping_method: shippingMethods } = usePageProps();

	if (!shippingMethods) {
		return null;
	}

	return (
		<div className="space-y-1">
			{shippingMethods.map((method) => {
				return <ShippingItem key={method.id} shipping={method} selected={selected} onSelect={onSelect} />
			})}
		</div>
	)
}

const ShippingItem = ({ shipping, selected, onSelect }) => {
	return (
		<div className="p-1 md:p-2 rounded-md border flex gap-4 items-center">
			<input type="radio" className="size-5" name="shipping" value={shipping.key} checked={shipping.key === selected} onChange={() => {
				onSelect(shipping.key);
			}} />
			<div className="w-full">
				<div className="flex justify-between w-full">
					<h3 className="font-semibold text-lg">{shipping.name} </h3>
					<h3 className="font-semibold text-lg text-orange-800">{formatCurrency(shipping.shipping_cost)} vnd </h3>
				</div>
				<p className="text-gray-400">{shipping.description}</p>
			</div>
		</div>
	);
}

export default ShippingInfo;