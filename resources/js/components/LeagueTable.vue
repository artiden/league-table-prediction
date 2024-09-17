<template>
    <div>
        <h2>League Table</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Team</th>
                    <th>Played</th>
                    <th>Won</th>
                    <th>Draw</th>
                    <th>Lost</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="team in teams" :key="team.id">
                    <td>{{ team.name }}</td>
                    <td>{{ team.played }}</td>
                    <td>{{ team.won }}</td>
                    <td>{{ team.drawn }}</td>
                    <td>{{ team.lost }}</td>
                    <td>{{ team.points }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        teams: Array,
        week: Number
    }
};
</script>
