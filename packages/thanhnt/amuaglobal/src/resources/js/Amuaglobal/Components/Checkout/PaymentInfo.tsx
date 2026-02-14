import { FunctionComponent, isValidElement, ReactElement, } from "react"
import { Radio } from "@material-tailwind/react";
import { useForm } from "@inertiajs/react";
import { Paypal, StripeSessionOnline, CheckMoney } from "./PaymentMethod";

type PaymentItem = {
	type: string;
	label: string;
	checked?: boolean;
	content: string | ReactElement
};

const paymentList: PaymentItem[] = [
	// {
	// 	type: 'bank',
	// 	label: 'Chuyển khoản ngân hàng',
	// 	content: 'Chuyển khoản ngân hàng',
	// 	checked: true,
	// },
	{
		type: 'check',
		label: 'Thanh toán tại quầy',
		content: <CheckMoney />,
		// content: 'Chúng tôi sẽ liên hệ qua điện thoại xác nhận với quý khách hàng trong 60 phút sau khi xác nhận thanh toán',
	},
	{
		type: 'paypal',
		label: 'Thanh toán qua paypal',
		content: <Paypal />,
	},
	{
		type: 'stripe',
		label: 'Thanh toán trực tiếp qua thẻ',
		content: <StripeSessionOnline />,
	},
];

type Props = {
	onSuccess: (response: any) => void;
	onError: (error: any) => void
}

const PaymentInfo: FunctionComponent<any> = ({ onSuccess, onError }) => {

	const { data, setData, } = useForm({
		paymentMethod: '',
		action: 'set-payment',
	})

	// const onSubmit = useCallback(() => {}, [data])

	return (
		<div className="flex justify-center md:grid-cols-2 w-full rounded-md p-1 ">
			<div className='w-full sm:max-w-lg p-4 shadow-md overflow-hidden rounded-md border-2 flex flex-col gap-y-4'>
				<span className="text-xl font-semibold">Thông tin thanh toán:</span>
				{paymentList.map((item, index) => {
					return (
						<div key={index.toString()} className="flex bg-white p-2 rounded-md shadow-md gap-x-2 items-center">
							{/* @ts-ignore */}
							<Radio name="type" checked={item.type === data.paymentMethod} readOnly onClick={() => {
								setData('paymentMethod', item.type);
							}} />
							<div className="w-full">
								<p className="text-md font-medium text-blue-500">{item.label}</p>
								{item.type === data.paymentMethod && (
									isValidElement(item.content) ? item.content : <p>{item.content}</p>
								)}
							</div>
						</div>
					)
				})}
			</div>

		</div>
	)
}

export default PaymentInfo