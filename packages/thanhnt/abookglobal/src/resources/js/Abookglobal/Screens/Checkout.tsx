import { Head, Link } from "@inertiajs/react";
import React, { createContext, FunctionComponent, useEffect, useMemo } from "react";
import { Stepper, Step, Typography, } from "@material-tailwind/react";
import {
	UserIcon,
	BuildingLibraryIcon,
	MapPinIcon,
} from "@heroicons/react/24/solid";
import BodyLayout from "../Layouts/BodyLayout";
import { sprintf } from "sprintf-js";
import Urls from "../network/Urls";
import { CustomerInfo, ShippingInfo, PaymentInfo, DatesInfo } from "@/Pages/Amuaglobal/Components/Checkout";
import { formatCurrency, convertStringServerToDates } from "@/Pages/Amuaglobal/Helper";

type CartData = {
	status: string,
	cart_type: string,
	cart_item: {
		id: number,
		image_path: string,
		name: string,
		price: number,
		qty: number,
	},
	currency_code: string,
	order_time: {
		date_from: string,
		date_to: string,
		selected_time: string[],
	},
	expect_order: string,
	total_price: number,
	shipping_method: any,
};

type Props = {
	cart: any;
	step?: 'payment' | 'customer-info';
}

const steps = [
	{
		position: 0,
		key: 'customer-info',
		component: <CustomerInfo onSuccess={() => { }} onError={() => { }}></CustomerInfo>,
		label: 'Thông tin khách hàng',
		icon: <UserIcon className="h-5 w-5" />,
	},
	{
		position: 1,
		key: 'customer-shipping',
		component: <ShippingInfo onSuccess={() => { }} onError={() => { }}></ShippingInfo>,
		label: 'Thông tin địa chỉ',
		icon: <MapPinIcon className="h-5 w-5" />,
	},
	{
		position: 2,
		key: 'payment',
		component: <PaymentInfo></PaymentInfo>,
		label: 'Thanh toán',
		icon: <BuildingLibraryIcon className="h-5 w-5" />
	},
]

type CheckoutProps = {
	cart: CartData,
	step?: 'payment' | 'customer-info',
};

const CheckoutContext = createContext<CheckoutProps>({
	cart: null,
	step: 'customer-info',
})

const Checkout: FunctionComponent<Props> = ({ cart, step, }: Props) => {
	const { customer_info, dateSelected } = cart;

	const [activeStep, setActiveStep] = React.useState(0);

	useEffect(() => {
		if (step) {
			const stepSelected = steps.find(s => s.key === step);
			if (stepSelected && customer_info) {
				setActiveStep(stepSelected.position);
			}
		}
	}, [])

	const selectedStep = useMemo(() => {
		return steps.find(step => step.position === activeStep);
	}, [activeStep])

	if (!cart) {
		return <BodyLayout contentClass="md:max-w-none">
			<div className="flex-1 bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center justify-center items-center flex">
				<div className="text-xl lg:text-2xl font-semibold text-orange-600">Đơn hàng không tồn tại!</div>
			</div>
		</BodyLayout>
	}

	return (
		<BodyLayout contentClass="md:max-w-none">
			<Head>
				<title>Thanh toán</title>
			</Head>
			<CheckoutContext.Provider value={{
				cart: cart,
				// book: book,
			}}>
				<div className="flex-1 bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center">
					<div className="w-full p-1 md:p-2 space-y-2">
						<OrderInfomation cart={cart}></OrderInfomation>
						<div className="flex flex-col gap-y-10">
							<div className="w-full justify-center flex">
								<div className="w-10/12 md:w-8/12 px-8">
									{/* @ts-ignore */}
									<Stepper
										activeStep={activeStep}
									>
										{steps.map(step => {
											return (
												// @ts-ignore
												<Step key={step.key}
													onClick={() => {
														if (step.position < selectedStep.position) {
															setActiveStep(step.position)
														}
													}}
												>
													{step.icon}
													{
														activeStep === step.position && <div className="absolute -bottom-[2rem] w-max text-center">
															{/* @ts-ignore */}
															<Typography
																variant="h6"
																color={activeStep === step.position ? "black" : "gray"}
															>
																{step.label}
															</Typography>
														</div>
													}
												</Step>
											)
										})}
									</Stepper>
								</div>
							</div>
							<div id="checkout-content">
								{React.cloneElement(selectedStep?.component, {
									onSuccess: () => {
										setActiveStep(selectedStep.position + 1);
									},
									onError: () => {
									},
								})}
							</div>
						</div>
					</div>
				</div>
			</CheckoutContext.Provider>
		</BodyLayout>
	)
}

const BookInfo = ({ book }) => {
	return (
		<div className="bg-gray-400 p-1 md:p-2 rounded-md">
			{/* <h3 className="text-md md:text-lg font-semibold my-1">Chi tiết:</h3> */}
			<div className="flex gap-2">
				<img src={book.image_path} className="size-32 lg:w-40 lg:h-40 rounded-md object-cover" alt="" />
				<Link href={sprintf(Urls.bookDetail, [book.id])}>
					<p className='text-md font-bold text-black '>{book.name}</p>
					<p className='text-md font-semibold '>Giá: {formatCurrency(book.price)}</p>
					<p className='text-md font-semibold '>Giới thiệu: {book.description}</p>
					<p className="text-md font-semibold text-blue-500">Số lượng: {book.qty}</p>
				</Link>
			</div>
		</div>
	);
}

const OrderInfomation = ({ cart }: { cart: CartData }) => {

	return (
		<div className="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-1">
			<BookInfo book={cart.cart_item} />
			<DatesInfo dateSelected={convertStringServerToDates(cart.order_time.selected_time)} totalPrice={cart.total_price} shippingMethod={cart.shipping_method}></DatesInfo>
		</div>
	);
}

export default Checkout