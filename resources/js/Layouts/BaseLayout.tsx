import { Head } from "@inertiajs/react";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { useEffect } from "react";

const queryClient = new QueryClient();

export default function Baselayout({ children }: { children: React.ReactNode }) {
	useEffect(()=>{
		/**
		 * App luôn luôn chạy với tất cả các thành phần từ thành phần gốc.
		 */
	}, [])
	return (
		<QueryClientProvider client={queryClient}>
			<Head>
			</Head>
			<div className="min-h-screen first-line:container mx-auto p-2 dark:bg-gray-300 bg-gray-100 full-height justify-between">
				{children}
			</div>
		</QueryClientProvider>
	)
}
