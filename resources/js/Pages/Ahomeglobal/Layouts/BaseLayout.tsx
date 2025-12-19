import React from "react";

const BaseLayout = ({ children }: { children: React.ReactNode }) => {
	return (
		<div className="flex flex-col md:pt-1 sm:justify-center sm:pt-0 container min-h-screen first-line:container mx-auto 
						p-2 py-1 dark:bg-gray-300 ">
			{children}
		</div>
	)
}

export default BaseLayout;