import { Head } from "@inertiajs/react";
import { PageLayout } from "../Layouts";
import React, { createContext, FunctionComponent, useEffect, useMemo } from "react";
import { Stepper, Step, Typography, } from "@material-tailwind/react";
import {
	UserIcon,
	BuildingLibraryIcon,
} from "@heroicons/react/24/outline";
import { HomeDetail } from "../types/Home";
import { RoomItem } from "../types/Room";
import { convertStringServerToDates } from "../Helper/DateTimeHelper";
import { DatesInfo, HomeInfo, RoomInfo, CustomerInfo, PaymentInfo } from "../Components/Checkout";
import BodyLayout from "../Layouts/BodyLayout";

type Props = {
	dateSelected: string[],
	home: HomeDetail,
	room?: RoomItem,
	totalPrice?: number;
	step?: 'payment' | 'customer-info';
	customer_info?: any;
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
		key: 'payment',
		component: <PaymentInfo></PaymentInfo>,
		label: 'Thanh toán',
		icon: <BuildingLibraryIcon className="h-5 w-5" />
	},
]

type CheckoutProps = {
	dateSelected: string[];
	homeDetail: HomeDetail;
	roomSelected?: RoomItem;
	totalPrice?: number;
};

const CheckoutContext = createContext<CheckoutProps>({
	dateSelected: [],
	homeDetail: undefined,
	roomSelected: undefined,
	totalPrice: undefined,
})

const Checkout: FunctionComponent<Props> = ({ dateSelected, home, room, totalPrice, step, customer_info }: Props) => {
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

	if (!home) {
		return <div>
			order not found
		</div>
	}

	return (
		<BodyLayout>
			<Head>
				<title>Thanh toán</title>
			</Head>
			<CheckoutContext.Provider value={{
				dateSelected: dateSelected,
				homeDetail: home,
				roomSelected: room,
			}}>
				<div className="flex-1 bg-white">
					<div className="w-full p-1 md:p-2 space-y-2">
						<OrderInfomation homeSelected={home} roomSelected={room} dateSelected={dateSelected} totalPrice={totalPrice}></OrderInfomation>
						<div className="flex flex-col gap-y-10">
							<div className="w-full justify-center flex">
								<div className="w-10/12 md:w-8/12">
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

const OrderInfomation = ({ homeSelected, roomSelected, dateSelected, totalPrice }: { homeSelected: HomeDetail, roomSelected?: RoomItem, dateSelected: string[], totalPrice: number }) => {

	return (
		<div className="grid grid-cols-1 md:grid-cols-3 gap-y-2 gap-x-1">
			<HomeInfo home={homeSelected}></HomeInfo>
			<RoomInfo room={roomSelected}></RoomInfo>
			<DatesInfo dateSelected={convertStringServerToDates(dateSelected)} totalPrice={totalPrice}></DatesInfo>
		</div>
	);
}

export default Checkout