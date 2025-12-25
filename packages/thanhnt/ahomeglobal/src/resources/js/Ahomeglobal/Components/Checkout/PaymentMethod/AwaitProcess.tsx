import { Dialog, DialogBody, DialogHeader } from "@material-tailwind/react";
import { size } from "@material-tailwind/react/types/components/dialog";
import React, { FunctionComponent } from "react";

type Props = {
	open: boolean,
	className?: string,
	size?: string,
	handleOpen?: () => void,
	bodyContent?: string | React.ReactElement,
	headerContent?: string | React.ReactElement
};

/**
 * AwaitProcess is a simple dialog component that displays a message
 * while a process is being executed.
 *
 * @returns {JSX.Element} - The dialog component.
 */
const AwaitProcess: FunctionComponent<Props> = ({ open, size, handleOpen, bodyContent, headerContent }) => {
	return (
		<Dialog
			open={open}
			size={(size || "md") as unknown as size}
			handler={handleOpen}
		>
			<DialogHeader>{headerContent}</DialogHeader>
			<DialogBody className="flex justify-center">
				{bodyContent}
			</DialogBody>
		</Dialog>
	)
}

export default AwaitProcess;