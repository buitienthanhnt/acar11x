import React from "react";
import BaseLayout from "./BaseLayout";
import { FooterWithLogo, HomeSpeed, NavbarDark } from "./Components";
import useMessage from '../hooks/useMessage';
import FlashMessage from "../Components/FlashMessage";

const BodyLayout = ({ children, showBanner }: { children: React.ReactNode, showBanner?: boolean }) => {
	const { error, success } = useMessage();

	return (
		<BaseLayout>
			<div className="flex flex-col sm:justify-center sm:pt-0 min-h-screen mx-auto 
						 dark:bg-gray-300 bg-[#f2f7fa]">
				<NavbarDark navStyles={'rounded-none max-w-full'}></NavbarDark>
				<div className="relative h-0 flex justify-end overflow-y-visible">
					{(error || success) && <FlashMessage message={error || success} type={error ? 'error' : 'success'}
						className="absolute top-4 flex right-0 w-auto ">
					</FlashMessage>}
				</div>

				{showBanner && <div className="w-full mx-auto bg-gradient-to-r from-blue-gray-900 to-blue-gray-800">
					<div className="min-h-36 md:min-h-[480px] p-4 bg-[url('/ahome/assets/img-1.jpg')] bg-cover bg-center"></div>
				</div>}

				<div className="flex flex-col flex-1 w-full md:container mx-auto px-1">
					{children}
					<HomeSpeed></HomeSpeed>
				</div>
				<FooterWithLogo></FooterWithLogo>
			</div>
		</BaseLayout>
	);
}

export default BodyLayout;