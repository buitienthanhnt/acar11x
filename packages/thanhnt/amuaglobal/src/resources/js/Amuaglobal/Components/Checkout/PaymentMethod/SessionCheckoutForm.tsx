import React, { useState } from "react";
import {
	PaymentElement,
	useCheckout
} from '@stripe/react-stripe-js/checkout';
import { Spinner } from "@material-tailwind/react";
import AwaitProcess from "./AwaitProcess";

const validateEmail = async (email, checkout) => {
	const updateResult = await checkout.updateEmail(email);
	const isValid = updateResult.type !== "error";

	return { isValid, message: !isValid ? updateResult.error.message : null };
}

const EmailInput = ({ checkout, email, setEmail, error, setError }) => {
	const handleBlur = async () => {
		if (!email) {
			return;
		}

		const { isValid, message } = await validateEmail(email, checkout);
		if (!isValid) {
			setError(message);
		}
	};

	const handleChange = (e) => {
		setError(null);
		setEmail(e.target.value);
	};

	return (
		<div className="flex w-full items-end py-2 space-x-2">
			<label className="text-xl font-medium">
				Email:
			</label>
			<input
				id="email"
				type="text"
				value={email}
				onChange={handleChange}
				onBlur={handleBlur}
				className={`${error ? "error" : ""} rounded-md border-1 flex-1`}
			/>
			{error && <div id="email-errors">{error}</div>}
		</div>
	);
};

const SessionCheckoutForm = () => {
	const [email, setEmail] = useState('');
	const [emailError, setEmailError] = useState(null);
	const [message, setMessage] = useState(null);
	const [isSubmitting, setIsSubmitting] = useState(false);

	const checkoutState = useCheckout();

	if (checkoutState.type === 'loading') {
		return (
			<div>Đang xử lý...</div>
		);
	}

	if (checkoutState.type === 'error') {
		return (
			<div>Lỗi: {checkoutState.error.message}</div>
		);
	}

	const handleSubmit = async (e) => {
		e.preventDefault();

		const { checkout } = checkoutState;
		setIsSubmitting(true);

		const { isValid, message } = await validateEmail(email, checkout);
		if (!isValid) {
			setEmailError(message);
			setMessage(message);
			setIsSubmitting(false);
			return;
		}

		const confirmResult = await checkout.confirm();

		// This point will only be reached if there is an immediate error when
		// confirming the payment. Otherwise, your customer will be redirected to
		// your `return_url`. For some payment methods like iDEAL, your customer will
		// be redirected to an intermediate site first to authorize the payment, then
		// redirected to the `return_url`.
		if (confirmResult.type === 'error') {
			setMessage(confirmResult.error.message);
		}

		setIsSubmitting(false);
	};

	return (
		<form onSubmit={handleSubmit}>
			<EmailInput
				checkout={checkoutState.checkout}
				email={email}
				setEmail={setEmail}
				error={emailError}
				setError={setEmailError}
			/>
			<PaymentElement id="payment-element" />
			<button disabled={isSubmitting} id="submit" className="bg-blue-400 shadow-md p-2 flex w-full justify-center mt-1 rounded-md hover:bg-blue-500">
				{isSubmitting ? (
					<div className="spinner">
						<Spinner color='red' className="w-4 h-4" onResize={undefined} onResizeCapture={undefined} onPointerEnterCapture={undefined} onPointerLeaveCapture={undefined}></Spinner>
					</div>
				) : (
					`Thanh toán: ${checkoutState.checkout.total.total.amount}`
				)}
			</button>
			{/* Show any error or success messages */}
			{message && <div id="payment-message">{message}</div>}
			<AwaitProcess
				open={isSubmitting}
				headerContent="Đang xử lý, vui lòng chờ phản hồi!"
				// @ts-ignore
				bodyContent={<Spinner className="h-12 w-12" />}
			></AwaitProcess>
		</form>
	);
}

export default SessionCheckoutForm;