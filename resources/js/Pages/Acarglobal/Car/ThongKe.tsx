import { Head } from "@inertiajs/react";
import ContentLayout from "../Layout/ContentLayout";
import React from "react";
import ReactApexChart from "react-apexcharts";
import { formatPrice } from "@/Helper/StringHelper";

const ThongKe = ({ car_fix_dones }: any) => {

    console.log(car_fix_dones);

    return (
        <ContentLayout>
            <Head title="thống kê"></Head>
            <div className="p-4 bg-gray-100">
                {/* <ApexChart></ApexChart>
                <ApexChartColumn></ApexChartColumn> */}
                <ApexChartLineColumn car_fix_dones={car_fix_dones}></ApexChartLineColumn>
            </div>
        </ContentLayout>
    )
}

export default ThongKe;

const ApexChart = () => {
    const [state, setState] = React.useState({

        series: [{
            name: "Desktops",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
        }],
        options: {
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            title: {
                text: 'Product Trends by Month',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            }
        },


    });



    return (
        <div>
            <div id="chart">
                <ReactApexChart options={state.options} series={state.series} type="line" height={350} />
            </div>
            <div id="html-dist"></div>
        </div>
    );
}

const ApexChartColumn = () => {
    const [state, setState] = React.useState({

        series: [{
            name: 'Inflation',
            data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2]
        }],
        options: {
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val + "%";
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },

            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function (val) {
                        return val + "%";
                    }
                }

            },
            title: {
                text: 'Monthly Inflation in Argentina, 2002',
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        },
    });



    return (
        <div>
            <div id="chart">
                <ReactApexChart options={state.options} series={state.series} type="bar" height={350} />
            </div>
            <div id="html-dist"></div>
        </div>
    );
}

const ApexChartLineColumn = ({ car_fix_dones }: { [key: string]: any[] }) => {
    let carCount: number[] = [];
    let totalCosts: number[] = [];

    /**
     * format collect data count of car length, total cost
     */
    Object.entries(car_fix_dones).forEach(([key, value]) => {
        carCount.push(value.length);
        totalCosts.push(value.reduce((a: any, b: any) => a + b.totalCost, 0));
    })

    const state = {
        series: [{
            name: 'Tổng doanh số',
            type: 'column',
            data: totalCosts
        }, {
            name: 'Số lượng xe hoàn thành',
            type: 'line',
            data: carCount
        }],
        options: {
            chart: {
                height: 350,
                type: 'line',
            },
            stroke: {
                width: [0, 4]
            },
            title: {
                text: 'Traffic Sources'
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: Object.keys(car_fix_dones),
            yaxis: [{
                title: {
                    text: 'Tổng doanh số',
                },

            }, {
                opposite: true,
                title: {
                    text: 'Số lượng xe hoàn thành'
                }
            }],
            tooltip: {
                y: [
                    {
                        // Formatter cho Series đầu tiên (Index 0)
                        formatter: function (val) {
                            return formatPrice(val);
                        }
                    },
                    {
                        // Formatter cho Series thứ hai (Index 1)
                        formatter: function (val) {
                            return val + " xe";
                        }
                    }
                ]
            }
        },
    }

    return (
        <ReactApexChart options={state.options} series={state.series} type="line" height={550} />
    );
}
