import { router } from "@inertiajs/react";
import React, { useEffect } from "react";

const BaseLayout = ({ children }: { children: React.ReactNode }) => {
  useEffect(() => {
    /**
     * Đoạn mã này lắng nghe sự kiện 'popstate' trên cửa sổ trình duyệt(khi người dùng nhấn nút Back trên thanh công cụ),
     * và khi sự kiện này xảy ra, nó sẽ gọi phương thức 'reload' của đối tượng 'router' để tải lại trang hiện tại.
     * Điều này có thể hữu ích để đảm bảo rằng khi người dùng nhấn nút Back, trang sẽ được làm mới và hiển thị nội dung mới nhất thay vì sử dụng phiên bản đã lưu trong bộ nhớ cache của trình duyệt.
     * Tuy nhiên, đoạn mã này đã bị comment lại, có thể do nhà phát triển đã quyết định không sử dụng nó hoặc đang trong quá trình thử nghiệm. 
     */
    // return window.addEventListener('popstate', () => {
    // // Khi nhấn nút Back trên thanh công cụ
    //   router.reload();
    // });
}, []);

	return (
		<div className="">
			{children}
		</div>
	)
}

export default BaseLayout;