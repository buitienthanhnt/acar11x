import { router } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

export default function TopBar() {
  const path = window.location.pathname;

  return (
    <div className="grid grid-cols-5 flex-1 p-2 bg-blue-gray-100 gap-1 rounded-lg">
      <Button variant="filled" color={path === '/acar' ? "blue" : "gray"} onClick={() => {
        router.visit('/acar');
      }}>
        Trang chủ
      </Button>
      <Button color={path === '/acar/xe-vao' ? "blue" : "gray"} variant="filled" onClick={() => {
        router.visit('/acar/xe-vao');
      }}>
        Xe vào
      </Button>
      <Button color={path === '/acar/car-list' ? "blue" : "gray"} variant="filled" onClick={() => {
        router.visit('/acar/car-list');
      }}>
        Danh sách xe
      </Button>
      <Button color={path === '/acar/cham-cong' ? "blue" : "gray"} variant="filled" onClick={() => {
        router.visit('/acar/cham-cong');
      }}>
        Chấm công
      </Button>
      <Button color={path === '/acar/thong-ke' ? "blue" : "gray"} variant="filled" onClick={() => {
        router.visit('/acar/thong-ke');
      }}>
        Thống kê
      </Button>
    </div>
  )
}