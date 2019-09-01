import numeral from 'numeral'

export function number_format(value) {
    return numeral(value).format("0,0.[0000]")
}

export default { number_format }
