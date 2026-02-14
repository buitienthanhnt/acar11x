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
		<div className="fixed overflow-auto inset-0 z-50" hidden={!open}>
			<span data-type="inside" aria-hidden="true" data-floating-ui-focus-guard="" data-aria-hidden="true"
				style={{ border: "0px", clip: "rect(0px, 0px, 0px, 0px)", height: "1px", margin: "-1px", overflow: "hidden", padding: "0px", position: "fixed", whiteSpace: "nowrap", width: "1px", top: "0px", left: "0px" }}
			>
			</span>
			<div className="grid place-items-center fixed w-screen h-screen bg-black bg-opacity-40"
				style={{ opacity: 1, }}
			>
				<div className="relative bg-white m-4 rounded-lg shadow-2xl text-blue-gray-500 antialiased font-sans text-base font-light leading-relaxed w-full md:w-3/4 lg:w-3/5 2xl:w-2/5 min-w-[90%] md:min-w-[75%] lg:min-w-[60%] 2xl:min-w-[40%] max-w-[90%] md:max-w-[75%] lg:max-w-[60%] 2xl:max-w-[40%] opacity-90" aria-labelledby=":r8:-label"
					style={{ opacity: 1, transform: "none" }}
				>
					<div className="flex items-center shrink-0 p-4 text-blue-gray-900 antialiased font-sans text-xl font-semibold leading-snug">{headerContent}</div>
					<div className="relative p-4 text-blue-gray-500 antialiased font-sans text-base font-light leading-relaxed flex justify-center">
						{bodyContent}
					</div>
				</div>
			</div>
			<span data-type="inside" aria-hidden="true" data-floating-ui-focus-guard="" data-aria-hidden="true"
				style={{ border: "0px", clip: "rect(0px, 0px, 0px, 0px)", height: "1px", margin: "-1px", overflow: "hidden", padding: "0px", position: "fixed", whiteSpace: "nowrap", width: "1px", top: "0px", left: "0px" }}
			></span>
		</div>
	)
}

export default AwaitProcess;