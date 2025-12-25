import { Head } from "@inertiajs/react";
import { PageLayout } from "../Layouts";
import React, { createContext, FunctionComponent, useEffect, useMemo } from "react";
import { Stepper, Step, Button, Typography, } from "@material-tailwind/react";
import {
	CogIcon,
	UserIcon,
	BuildingLibraryIcon,
} from "@heroicons/react/24/outline";
import { HomeDetail } from "../types/Home";
import { RoomItem } from "../types/Room";
import { convertStringServerToDates } from "../Helper/DateTimeHelper";
import { DatesInfo, HomeInfo, RoomInfo, CustomerInfo, PaymentInfo } from "../Components/Checkout";

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
		label: 'customer info',
		icon: <UserIcon className="h-5 w-5" />,
	},
	{
		position: 1,
		key: 'payment',
		component: <PaymentInfo></PaymentInfo>,
		label: 'payment info',
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
	const [isLastStep, setIsLastStep] = React.useState(false);
	const [isFirstStep, setIsFirstStep] = React.useState(false);

	const handleNext = () => !isLastStep && setActiveStep((cur) => cur + 1);
	const handlePrev = () => !isFirstStep && setActiveStep((cur) => cur - 1);

	useEffect(() => {
		console.log('----->', step, customer_info);
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
		<PageLayout>
			<Head>
				<title>Checkout</title>
			</Head>
			<CheckoutContext.Provider value={{
				dateSelected: dateSelected,
				homeDetail: home,
				roomSelected: room,
			}}>
				<div className="bg-blue-gray-300 flex-1">
					<div className="w-full p-1 md:p-2 space-y-2">
						<OrderInfomation homeSelected={home} roomSelected={room} dateSelected={dateSelected} totalPrice={totalPrice}></OrderInfomation>
						<div className="flex flex-col gap-y-10">
							<div className="w-full justify-center flex">
								<div className="w-10/12 md:w-8/12">
									<Stepper
										activeStep={activeStep}
										isLastStep={(value) => setIsLastStep(value)}
										isFirstStep={(value) => setIsFirstStep(value)}
									>
										{steps.map(step => {
											return (
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

						{/* <div className="mt-32 flex justify-between">
						<Button onClick={handlePrev} disabled={isFirstStep}>
							Prev
						</Button>
						<Button onClick={handleNext} disabled={isLastStep}>
							Next
						</Button>
					</div> */}
					</div>
				</div>
			</CheckoutContext.Provider>
		</PageLayout>
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