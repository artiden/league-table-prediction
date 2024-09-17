<template>
    <div>
        <h2>Future win probability</h2>
        <table>
            <thead>
            <tr>
                <th>Team</th>
                <th>Win probability</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="prediction in predictions" :key="prediction.team.id">
                <td>{{ prediction.team.name }}</td>
                <td>{{ prediction.win_probability }}%</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        predictions: Array
    }
};
</script>
