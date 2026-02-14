import React, { CSSProperties, } from "react";
import BaseLayout from "./BaseLayout";
import { FooterWithLogo, HomeSpeed, NavbarDark } from "./Components";
import useMessage from '../hooks/useMessage';
import FlashMessage from "../Components/FlashMessage";

const BodyLayout = ({ children, showBanner, mainStyles, contentClass }: { children: React.ReactNode, showBanner?: boolean, mainStyles?: CSSProperties | undefined, contentClass?: string }) => {

	return (
		<BaseLayout>
			<div className="flex flex-col sm:justify-center sm:pt-0 min-h-screen mx-auto 
						 dark:bg-gray-300 "  style={mainStyles}>
				<NavbarDark navStyles={'rounded-none max-w-full'}></NavbarDark>
				<Messages></Messages>
				{showBanner && <div className="w-full mx-auto bg-gradient-to-r from-blue-gray-900 to-blue-gray-800">
					<div className="min-h-36 md:min-h-[480px] p-4 bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center"></div>
				</div>}
				<div className={`flex flex-col flex-1 w-full md:container mx-auto ${contentClass}`}>
					{children}
					<HomeSpeed></HomeSpeed>
				</div>
				<FooterWithLogo></FooterWithLogo>
			</div>
		</BaseLayout>
	);
}

const Messages = () => {
	const { error, success, errors } = useMessage();

	const errorContents: string[] = [];
	for (const [key, value] of Object.entries(errors)) {
		errorContents.push(value as string);
	}

	return (
		<div className="relative h-0 flex justify-end overflow-y-visible">
			{(error || success) && <FlashMessage message={error || success} type={error ? 'error' : 'success'}
				className="absolute top-4 flex right-0 w-auto ">
			</FlashMessage>}
			{errorContents && <div className="relative space-y-1 justify-end overflow-y-visible">
				{errorContents.map((error, index) => <FlashMessage message={error} key={index} type="error"></FlashMessage>)}
			</div>}
		</div>

	)
}

export default BodyLayout;