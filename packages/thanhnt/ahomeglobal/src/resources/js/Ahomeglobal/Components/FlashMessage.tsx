import { Alert } from "@material-tailwind/react";
import { FunctionComponent, useEffect, useState } from "react";

interface FlashMessageInterface {
	message?: string;
	type: string,
	className?: string,
}
const FlashMessage: FunctionComponent<FlashMessageInterface> = ({ message, type = 'messsges', className }) => {
	const [show, setShow] = useState<boolean>(true);

	useEffect(() => {
		const autoHide = setTimeout(() => {
			show && setShow(false);
		}, 2000);

		return () => clearTimeout(autoHide);
	}, [show])

	if (!show || !message) {
		return;
	}

	return <div className={className}>
		<Alert color={type === 'error' ? "red" : 'green'} className="text-right" variant="gradient">{message}</Alert>
	</div>
}

export default FlashMessage;